# -*- coding: utf-8 -*-
from httplib import HTTPConnection, HTTPSConnection
import re
import base64
from xml.parsers.expat import ParserCreate
from django.http import HttpResponseRedirect, HttpResponse
from django.shortcuts import render_to_response
from payga import configuracion_de_pago as conf
def obtener_datos():
	mi_url = conf.url_preregistro.replace('@afiliacion@', conf.afiliacion)
	mi_url = mi_url.replace('@factura@', conf.factura)
	mi_url = mi_url.replace('@monto@', conf.monto)
	datos = { 'servidor': conf.servidor, 'puerto': conf.puerto,
		'user': conf.user, 'password': conf.password,
		'https': conf.https, 'url_preregistro': mi_url,
		'url_verificacion': conf.url_verificacion,
		'url_redirect': conf.url_redirect, 'afiliacion': conf.afiliacion,
		'factura': conf.factura, 'monto': conf.monto }
	return datos
def home(request):
	return render_to_response('mostrar_datos_compra.html', obtener_datos())
def pagar(request):
	datos = obtener_datos()
	coneccion = None
	url_preregistro = datos['url_preregistro']
	try:
		if datos['https']:
			coneccion = HTTPSConnection(datos['servidor'], datos['puerto'])
		else:
			coneccion = HTTPConnection(datos['servidor'], datos['puerto'])
		credenciales = datos['user'] + ':' + datos['password']
		credenciales = base64.b64encode(credenciales)
		coneccion.request('GET', datos['url_preregistro'], '', { 'Authorization': "Basic %s" % credenciales })
		respuesta = coneccion.getresponse()
		control = respuesta.read()
	except Exception as e:
		return HttpResponse('Conexión negada por el servidor %s:%s, %s' % (datos['servidor'], datos['puerto'], str(e)))
	while control[-1] == '\n':
		control = control[:-1]
	try:
		int(control)
	except:
		return HttpResponse(control)
	else:
		url_redirect = datos['url_redirect'].replace('@control@', control)
		return HttpResponseRedirect(url_redirect)
def verificar(request, control):
	datos = obtener_datos()
	url_verificacion = datos['url_verificacion']
	url_verificacion = url_verificacion.replace('@control@', control)
	try:
		if datos['https']:
			coneccion = HTTPSConnection(datos['servidor'], datos['puerto'])
		else:
			coneccion = HTTPConnection(datos['servidor'], datos['puerto'])
		credenciales = datos['user'] + ':' + datos['password']
		credenciales = base64.b64encode(credenciales)
		coneccion.request('GET', url_verificacion, '', { 'Authorization': "Basic %s" % credenciales })
		respuesta = coneccion.getresponse()
		resultado = respuesta.read()
		p = ParserCreate('ISO-8859-1')
		p.Parse(resultado)
	except Exception as e:
		return HttpResponse('Conexión negada por el servidor %s:%s, %s' % (datos['servidor'], datos['puerto'], str(e)))
	return render_to_response('resultado.html', { 'resultado': resultado })
	