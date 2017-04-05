# -*- coding:utf-8 -*-
import os
import psutil
from fabric.api import local, settings, abort, run, env
from fabric.contrib.console import confirm
from fabric.context_managers import cd, settings, hide

USER = 'categulario'
HOST = 'carapacho.org'
APP_NAME = 'tbots.categulario.tk'

# Host and login username:
env.hosts = ['%s@%s' % (USER, HOST)]

APP_ROOT = "/home/{}/webapps/{}".format(USER, APP_NAME)

def install_dependencies():
    """Instala las nuevas dependencias del paquete en el servidor remoto"""
    with cd(APP_ROOT):
        run("composer install")

def pull():
    with cd(APP_ROOT):
        run("git pull")

def memory():
    """Monitorea la memoria usada"""
    run("ps -u %s -o pid,rss,command | awk '{print $0}{sum+=$2} END {print \"Total\", sum/1024, \"MB\"}'"%USER)

def deploy():
    """Actualiza el servidor de producción"""
    pull()
    install_dependencies()
