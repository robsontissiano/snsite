<?php

	/**
	* Retrieve and cache a Twitter user timeline
	* 
	* Please go to https://dev.twitter.com, log in with your
	* Twitter account and create a new application (read-only)
	* to generate the required access tokens.
	*
	* Twitter API Exchange by James Mallison <me@j7mbo.co.uk>
	* 2013 (c) Serifly.com
	*/
	
	$username = 'serifly';
	
	$consumer_key = '';
	$consumer_secret = '';
	
	$access_token = '';
	$access_token_secret = '';
	
	/**
	* Configuration ends, please do not edit any of the following
	* code unless you know what you are doing.
	*/
	
	error_reporting(0);
	ini_set('display_errors', 0);
	define('CACHE_INTERVAL', 15);
	define('CACHE_FILE', 'twitter-' . $username . '.cache');
	
	function last_cache()
	{
		if (file_exists(CACHE_FILE) && is_readable(CACHE_FILE))
		{
			$handle = fopen(CACHE_FILE, 'r');
			$read_data = fgets($handle);
			fclose($handle);
			
			return $read_data;
		}
		else
		{
			return false;
		}
	}
	
	function read_cache()
	{
		if (file_exists(CACHE_FILE) && is_readable(CACHE_FILE))
		{
			$handle = fopen(CACHE_FILE, 'r');
			$read_data = fgets($handle);
			$read_data = '';
			while (!feof($handle)) $read_data = fgets($handle);
			fclose($handle);
			
			return $read_data;
		}
		else
		{
			return false;
		}
	}
	
	function update_cache($write_data)
	{
		if (is_writable(dirname(CACHE_FILE)))
		{
			$handle = fopen(CACHE_FILE, 'w');
			$write_data = time() . "\r\n" . $write_data;
			fwrite($handle, $write_data);
			fclose($handle);
			
			return true;
		}
		else
		{
			return false;
		}
	}
		
	if ((time() - last_cache()) > (60 * CACHE_INTERVAL))
	{
		require_once('twitter-api.php');
		
		$twitter_api = new TwitterAPIExchange(array
		(
			'consumer_key' => $consumer_key,
			'consumer_secret' => $consumer_secret,
			'oauth_access_token' => $access_token,
			'oauth_access_token_secret' => $access_token_secret
		));
		
		if ($response = $twitter_api->setGetfield('?screen_name=' . $username . '&count=10')->buildOauth('https://api.twitter.com/1.1/statuses/user_timeline.json', 'GET')->performRequest())
		{		
			update_cache($response);
			echo $response;
			exit();
		}
	}
	
	echo read_cache();