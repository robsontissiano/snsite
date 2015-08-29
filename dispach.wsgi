import os, sys

sys.path.append(os.path.dirname(__file__).replace('\\','/'))
os.environ['DJANGO_SETTINGS_MODULE'] = 'soner.settings'

from django.core.handlers.wsgi import WSGIHandler
application = WSGIHandler()
