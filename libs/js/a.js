const rgxtxt="[a-zA-Z0-9]{0,99}";
const rgxmail = "^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$";
const rgxdfnum = "[0-9]{0,18}";
const rgxphone = /^(3\d{9}|[0-9]{7})$/;
const rgxphone1 = /^(3\d{9}|[0-9]{7}|0)$/;
const rgxsisben= /^(0|[1-9]|1\d|20|21)$/;
const rgxpeso= /^(?:(?:0\.(?:50|[6-9]\d?))|(?:[1-9]\d{0,2}(?:\.\d{1,2})?)|(?:150(?:\.00?)?))$/;//0.50-150
const rgxtalla= /^(?=\d{2,3}(?:\.\d{1,2})?$)\d+(\.\d{1,2})?$/;//20-210
const rgxsisto= /^(?:[6-9]\d|1\d\d|30\d|31\d|310)$/; //60-310
const rgxdiast= /^(?:[4-9]\d|1[0-7]\d|18[0-5])$/; //40-185
const rgxgluco= /^(?:[5-9]|[1-9]\d{1,2}|1[0-5]\d{2}|1600)$/;//5-600
const rgxperabd= /^(?=\d{2,3}(?:\.\d{1,2})?$)\d+(\.\d{1,2})?$/;//45-180
const rgx1codora=/^(?:[1-7]|)$/;//1-7
const rgx3in1fl="^(\d{2,3}\.\d{1})$";

var rgxdatehms = "([12][0-9][0-9][0-9])-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01]) ([01][0-9]|2[0123]):([0-5][0-9]):([0-5][0-9])";
var rgxdatehm = "([12][0-9][0-9][0-9])-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01]) ([01][0-9]|2[0123]):([0-5][0-9])";
var rgxdate = "([12][0-9][0-9][0-9])-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])";
var rgxtime = "([01][0-9]|2[0123]):([0-5][0-9])";

window.appVersion = "1.03.29.1";

const version = document.querySelector("div.usuario");

if (version) {
  const actual = version.textContent;
  let ver=actual.split("_");
   // Verificar si la versión en el div.usuario es igual a window.appVersion
   
  if (ver[1] !== window.appVersion) {
	alert('Por favor recuerda borrar tu Cache, para utilizar la versión más estable del sistema '+window.appVersion);
	window.location.href = '/logout.php';
	exit;
    version.textContent = actual + '_' + window.appVersion;
  }
}

document.addEventListener('keydown', function (event) {
	if (event.ctrlKey && event.key === 'v') {
		inform('Esta acción no esta permitida');
	  event.preventDefault();
	}
  });

   document.addEventListener('contextmenu', function (event) {
	inform('Esta acción no esta permitida');
	event.preventDefault();
  }); 
  

//Sesion
let inactivityTimer;

function startInactivityTimer() {
  const inactivityTimeout = 60 * 60 * 1000;
  inactivityTimer = setTimeout(function() {
    window.location.href = '/logout.php';
  }, inactivityTimeout);
}

function resetInactivityTimer() {
  clearTimeout(inactivityTimer);
  startInactivityTimer();
}

document.addEventListener("click", resetInactivityTimer);
document.addEventListener("keypress", resetInactivityTimer);
startInactivityTimer();


function countMaxChar(ele, max=5000) {
	ele.addEventListener("input", function() {
		var longitud = this.value.length;
		if (longitud > max){
			this.value=this.value.slice(0,max);
			warnin("Has ingresado más de " + max + " caracteres en el campo");
		}
	});
}

var captura = {
	init: function (n, c = '', a = 'tab') {
		var con = "";
		if (a == 'lib')
			con += "<div id='"+n+"-filtro' class='col-2 menu-filtro'></div><div class='col-8 panel'>";
		con += "<div id='"+n+"-captura' class='contenido "+a+" col-7 datos'></div>";
		con += "<div class='col-3 padding10'><span class='mensaje' id='"+n+"-msj' ></span><br>"+panel_ayuda()+"</div></div>";//CARLOS ACEVEDO  QUITAR border: 1px solid #666;
		con += "<div id='"+n+"-tot' class='col-0 contenido' ></div>";
		con += "<div id='"+n+"-lis' class='col-0 contenido'></div>";
		if (a == 'lib')
			con += "</div>";
		document.getElementById(n+'-'+a+'-con').innerHTML = con;
		if (c != '')
			this.head(n, c, 'captura');
		if (a != 'lib')
			act_lista(n, a);
	},
	head: function (n, c, p = 'captura') {
		var cab = "";
		var cmp = "";
		var foc = c[0].n;
		for (var i = 0; i < c.length; i++) {
			if (c[i].t == undefined)
				c[i].t = 't';    //tipo
			if (c[i].x == undefined)
				c[i].x = '';     //regexp  
			if (c[i].h == undefined)
				c[i].h = '';     //holder
			if (c[i].p == undefined)
				c[i].p = '';     //parent
			if (c[i].d == undefined)
				c[i].d = '';	 //default
			if (c[i].s == undefined)
				c[i].s = 8;	     //size
			if (c[i].v == undefined)
				c[i].v = true;   //valid	  
			if (c[i].i == undefined)
				c[i].i = false;  //insert
			if (c[i].c == undefined)
				c[i].c = c[i].n; //clase
			if (c[i].f == undefined)
				c[i].f = false;  //focus 
			switch (c[i].t) {
				case 's':
					cmp = textsel(n, c, i, p);
					break;
				default:
					cmp = textbox(n, c, i, p);
			}
			document.getElementById(n+'-'+p).innerHTML += "<div class='col-10 campo borde1 oscuro'><div>"+c[i].l+"</div>"+cmp+"</div>";
			if (c[i].t == 's') {
                if (document.getElementById('lista_'+c[i].c) == undefined)
				 act_html(c[i].n, ruta_app, 'tb='+c[i].c+'&a=opc&id='+c[i].d, false);
            }   
		}
		if (p == 'filtro')
			act_lista(n);
		document.getElementById(foc).focus();
	},
	mod: function (n, c, r, keys = false) {
		var foc = c[0].n;
		if (keys) {
			console.log(r);
			var i = 0;
			for (var key in r) {
				var cmp = document.getElementById(c[i].n);
				if (c[i].u == undefined)
					c[i].u = true;
				if (c[i].f == undefined)
					c[i].f = false;
				if (c[i].d == undefined)
					c[i].d = '';
				if (c[i].u) {
					if (cmp.classList.contains('captura')) {
						cmp.classList.remove('captura');
						cmp.classList.add('bloqueo');
					}
				}
				document.getElementById(c[i].n).readOnly = c[i].u;
				document.getElementById(c[i].n).value = r[key];
				if (c[i].f)
					foc = c[i].n;
				i++;
			}
		} else {
			for (i = 0; i < c.length; i++) {
				var cmp = document.getElementById(c[i].n);
				if (c[i].u == undefined)
					c[i].u = true;
				if (c[i].f == undefined)
					c[i].f = false;
				if (c[i].d == undefined)
					c[i].d = '';
				if (c[i].u) {
					cmp.disabled = true;
					if (cmp.classList.contains('captura')) {
						cmp.classList.remove('captura');
						cmp.classList.add('bloqueo');
					}
				}
				document.getElementById(c[i].n).readOnly = c[i].u;
				document.getElementById(c[i].n).value = r[i];
				if (c[i].f)
					foc = c[i].n;
			}
		}
		document.getElementById(foc).ftocus();
	},
	lim: function (n, c) {
		var foc = c[0].n;
		for (i = 0; i < c.length; i++) {
			var cmp = document.getElementById(c[i].n);
			if (c[i].i == undefined)
				c[i].i = false;
			if (c[i].p == undefined)
				c[i].p = '';
			if (c[i].d == undefined)
				c[i].d = '';
			if (c[i].f == undefined)
				c[i].f = false;
			cmp.disabled = false;
			if (cmp.classList.contains('bloqueo')) {
				cmp.classList.remove('bloqueo');
				cmp.classList.add('captura');
			}
			if (c[i].i) {
				if (cmp.classList.contains('captura')) {
					cmp.classList.remove('captura');
					cmp.classList.add('bloqueo');
				}
			}
			cmp.readOnly = c[i].i;
			cmp.value = (c[i].p != '' ? valor(c[i].p)+c[i].d : c[i].d);
			if (c[i].f)
				foc = c[i].n;
		}
		document.getElementById(foc).focus();
	}
};

function solo_numero(e) {
	var unicode = e.charCode ? e.charCode : e.keyCode
	if (unicode != 8 & unicode != 9) {
		if ((unicode < 48 || unicode > 57))
			return false
	}
}

function solo_numeroFloat(e) {
	var unicode = e.charCode ? e.charCode : e.keyCode;
	if ((unicode >= 48 && unicode <= 57) || unicode === 46) {
	  var inputValue = e.target.value;
	  if (unicode === 46 && inputValue.indexOf('.') !== -1) {
		return false;
	  }
	} else if (unicode !== 8 && unicode !== 9) {
	  return false;
	}
  }
  

function solo_fecha(e) {
	var unicode = e.charCode ? e.charCode : e.keyCode
	if (unicode != 8 && unicode != 9) {
		if ((unicode < 45 || unicode > 58) && (unicode!=32))
			return false
	}
}
function solo_hora(e) {
	var unicode = e.charCode ? e.charCode : e.keyCode
	if (unicode != 8 && unicode != 9) {
		if ((unicode < 48 || unicode > 58))
			return false
	}
}
function solo_reg(a, b='[A..Z]',inver=false) {
	var r = RegExp(b);
    a.classList.remove('alerta');
	a.classList.remove('invalid');
	if (!r.test(a.value))
		a.classList.add('alerta');
		a.classList.add('invalid');
}

function checkon(a) {
	if (a.value == 'NO')
		a.value = 'SI';
	else
		a.value = 'NO';
}

function is_option(a) {
	for (var i = 0; i < a.list.options.length; i++)
		if (a.list.options[i].value == a.value)
			return true;
	return false;
}

function valido(a) {
	a.classList.remove('alerta','invalid');
	if (a.multiple)document.querySelector('select[name="'+a.id+'[]"]').previousElementSibling.classList.remove('alerta');
		if (a.value == '') a.classList.add('alerta','invalid');
		if (a.value == '' && a.multiple)document.querySelector('select[name="'+a.id+'[]"]').previousElementSibling.classList.add('alerta');
		/* if(a.tagName=='SELECT'){
			if(a.firstChild.classList.contains('alerta') && a.value =='0')a.classList.add('alerta','invalid');
		} */
	if (a.list != undefined && a.value != '' && !is_option(a)) a.classList.add('alerta','invalid');
	if (a.type=='date' || a.type=='time' || a.type=='datetime-local' || a.type=='datetime' ){ if ((a.min!='' || a.max!='') && (a.value<a.min || a.value>a.max)){ 
		errors('El valor del campo debe ser igual o Posterior a ('+a.min+') ó igual o Inferior a ('+a.max+'), por favor valide para continuar.');
		a.classList.add('alerta','invalid');}}
		// validaReg(a);
	if (!a.classList.contains('alerta','invalid')) return true;
	else return false;
}

/* function validaReg(a) {
	if (a.getAttribute('onBlur')) {
		const fun = a.getAttribute('onBlur');
		const regex = /,\s*([^,\s)]+)/;
		const param = fun.match(regex);
		if (param && param[1]) {
			const segpar = param[1].replace(/^['"]|['"]$/g, '');
			solo_reg(a,segpar);
  		}
	}
} */

function errors(msj){
	document.getElementById(mod+'-modal').innerHTML = msj;
	document.getElementById(mod+'-image').innerHTML = '<div class="icon-popup rtabad" ></div>';
	openModal();
}
function ok(msj){
	document.getElementById(d+'-modal').innerHTML=msj;
	document.getElementById(mod+'-image').innerHTML = '<div class="icon-popup rtaok"></div>';
}

function inform(msj){
	document.getElementById(mod+'-modal').innerHTML = msj;
	document.getElementById(mod+'-image').innerHTML = '<div class="icon-popup rtainfo" ></div>';
	openModal();
}
function questi(msj){
	document.getElementById(mod+'-modal').innerHTML = msj;
	document.getElementById(mod+'-image').innerHTML = '<div class="icon-popup rtaquest" ></div>';
	openModal();
}
function warnin(msj){
	document.getElementById(mod+'-modal').innerHTML = msj;
	document.getElementById(mod+'-image').innerHTML = '<div class="icon-popup rtawarn" ></div>';
	openModal();
}


function valor(a, b) {
	var x=document.getElementById(a);
	if (x==undefined) var x=parent.document.getElementById(a);
	if (b!=undefined && x!=undefined) x.value=b;
	if (x!=undefined) {
		if (x.value=='') return x.value;
		if (!isNaN(x.value)) return parseInt(x.value);
		else return x.value;
	}
}

function ir_pagina(tb, p, t) {
	if ((p > 0) && (p <= t))
		document.getElementById('pag-'+tb).value = p;
	act_lista(tb, document.getElementById('pag-'+tb));
}

function ir_pag(tb, p, t,mo) {
	if ((p > 0) && (p <= t))
		document.getElementById('pag-'+tb).value = p;
	act_lista(tb, document.getElementById('pag-'+tb),mo);
}

function mostrar(tb, a='', ev, m='', lib=ruta_app, w=7, tit='', k='0') {
	var id = tb+'-'+a;
	if (a == 'pro') {
        if (ev!=undefined) {tit=ev.currentTarget.title;k=ev.target.id;}
		crear_panel(tb, a, w, lib, tit);
        act_html(id+'-con',lib,'a=cmp&tb='+tb+'&id='+k);        
	}
	if (a == 'fix') {
        if (ev!=undefined) {tit=ev.currentTarget.title;k=ev.target.id;}
		panel_fix(tb, a, w, lib, tit);
        act_html(id+'-con',lib,'a=cmp&tb='+tb+'&id='+k);        
	}
	if (a == 'sta') {
        if (ev!=undefined) {tit=ev.currentTarget.title;k=ev.target.id;}
		panel_static(tb, a, w, lib, tit);
        act_html(id+'-con',lib,'a=cmp&tb='+tb+'&id='+k);        
	}
    if (document.getElementById(id+'-msj')!=undefined) document.getElementById(id+'-msj').innerHTML="";
	if (document.getElementById(tb+'-msj')!=undefined) document.getElementById(tb+'-msj').innerHTML="";
    foco(inner(id+'-foco'));
}

function crear_panel(tb, a, b = 7, lib = ruta_app, tit = '') {
	var id = tb+'-'+a;
	if (document.getElementById(id) == undefined) {
		var p = document.createElement('div');
		p.id = id;
		p.className = a+' panel'+(a=='frm'?'col-0':' movil col-'+b);
		var txt = "<div id='"+id+"-tit' class='titulo'><span>"+(tit==''?tb.replace('_', ' '):tit)+"</span>";
		txt += "<span id='"+id+"-foco' class='oculto'></span>";
		/* txt += "<input id='"+id+"-file' type=hidden readonly style='background:none;color:white;' >"; */
		txt += "<nav class='left'><ul class='menu' id='"+id+"-menu'></ul></nav><nav class='menu right'><li class='icono "+tb+ " cancelar' title='Cerrar' Onclick=\"ocultar('"+tb+"','"+a+"');\"></li></nav></div>";
		txt += "<span id='"+id+"-msj' class='mensaje' ></span>";
        txt += "<div class='contenido "+(a=='lib'?'lib-con':'')+"' id='"+id+"-con' ></div>";
		p.innerHTML = txt;
		document.getElementById('fapp').appendChild(p);
		Drag.init(document.getElementById(id+'-tit'),p);
		document.getElementById(id).style.top=(screen.height-p.style.height)/7;
		document.getElementById(id).style.left=(screen.width-p.style.width)/10.5;
        act_html(id+'-menu',lib,'tb='+tb+'&a=men&b='+a, false);
        act_html(id+'-foco',lib,'tb='+tb+'&a=focus&b='+a, false); 
	}
	document.getElementById(id).style.display = "block";	
	//document.getElementById(id+"-con").innerHTML="";		
}

function panel_fix(tb, a, b = 7, lib = ruta_app, tit = '') {
	var id = tb+'-'+a;
	if (document.getElementById(id) == undefined){
		var p = document.createElement('div');
		p.id = id;
		p.className = a+' panel'+(a=='fix'?' col-8':' col-'+b);
		var txt = "<div class='contenido "+(a=='lib'?'lib-con':'')+"' id='"+id+"-con' ></div>";
		p.innerHTML = txt;
		document.getElementById('fapp').appendChild(p);
		Drag.init(document.getElementById(id+'-con'),p);
		document.getElementById(id).style.top=(screen.height-p.style.height)/7;
		document.getElementById(id).style.left=(screen.width-p.style.width)/10.5;
        act_html(id+'-menu',lib,'tb='+tb+'&a=men&b='+a, false);
        act_html(id+'-foco',lib,'tb='+tb+'&a=focus&b='+a, false); 
	}
	document.getElementById(id).style.display = "block";	
}

function panel_static(tb, a, b = 7, lib = ruta_app, tit = '') {
	var id = tb+'-'+a;
	if (document.getElementById(id) == undefined) {
		var p = document.createElement('div');
		p.id = id;
		p.className = a+' panel'+(a=='frm'?'col-0':' static col-'+b);
		var txt = "<div id='"+id+"-tit'>";
		txt += "<span id='"+id+"-foco' class='oculto'></span>";
		txt += "<nav cass='left'></nav><nav class='menu right'></nav></div>";
		txt += "<span id='"+id+"-msj' class='mensaje' ></span>";
        txt += "<div class='contenido "+(a=='lib'?'lib-con':'')+"' id='"+id+"-con' ></div>";
		p.innerHTML = txt;
		document.getElementById('fapp').appendChild(p);
		Drag.init(document.getElementById(id+'-tit'),p);
		document.getElementById(id).style.top=(screen.height-p.style.height)/7;
		document.getElementById(id).style.left=(screen.width-p.style.width)/10.5;
        act_html(id+'-menu',lib,'tb='+tb+'&a=men&b='+a, false);
        act_html(id+'-foco',lib,'tb='+tb+'&a=focus&b='+a, false); 
	}
	document.getElementById(id).style.display = "block";	
	//document.getElementById(id+"-con").innerHTML="";		
}

function foco(a){
    if (document.getElementById(a)!=undefined) document.getElementById(a).focus();
}
function inner(a){
    if (document.getElementById(a)!=undefined) {
        var b=document.getElementById(a).innerHTML;
        return b.replace(/(\r\n|\n|\r)/gm, "");
    }    
}
function ocultar(tb, a) {
	if (document.getElementById(tb+'-'+a) != undefined) {
		can_children(tb, a);
		if (a == 'tab')
			ocultar(tb, 'cap');
		if (a != 'panel')
			if (document.getElementById(tb+'-'+a+'-con') != undefined)
				document.getElementById(tb+'-'+a+'-con').innerHTML = "";
		document.getElementById(tb+'-'+a).style.display = "none";
	}
}
function can_children(tb, a) {
	if (a == 'cap') {
		var id = tb+'-'+a;
		var c = document.getElementById(id+'-menu');
		if (c != undefined) {
			for (var b = 0; b < c.children.length; b++) {
				if (c.children[b].id.indexOf('mostrar') >= 0) {
					var d = c.children[b].id.substr(c.children[b].id.indexOf('mostrar')+8);
					ocultar(d, 'tab');
					ocultar(d, 'gra');
				}
			}
		}
		if (document.getElementById('indicador-objeto') != undefined)
			ocultar(document.getElementById('indicador-objeto').value.toLowerCase(), 'tab');
	}
}
function plegarPanel(t,a){
	var d=document.getElementsByClassName(t+' '+a);
	for (i=0;i<d.length;i++) {
		if(d[i].style.display == 'none'){
			d[i].style.display = 'block';
		}else{
			d[i].style.display = 'none';
		}
	} 
	var icono=document.getElementById(a);
	if(icono.classList.contains('desplegar-panel')){
		icono.classList.remove('desplegar-panel');
		icono.classList.add('plegar-panel');
		icono.title='Mostrar';
	}else{
		icono.classList.remove('plegar-panel');
		icono.classList.add('desplegar-panel');
		icono.title='Ocultar';
	}
}
function desplegar(a) {
	if (document.getElementById(a) != undefined) {
		var b = document.getElementById(a);
		if (b.style.display == 'none') {			
            var left = (screen.width - b.style.width) / 2;
            var top = (screen.height - b.style.height) / 2;        
            b.top=top;
            b.left=left;
            b.style.display = 'block';
        }   
		else
			b.style.display = 'none';
	}
}

function act_lista(tb, b,lib = ruta_app) {
	if (document.getElementById(tb+'-msj') != undefined)
		valor(tb+'-msj', '...');
	if (document.getElementById(tb+'-lis') != undefined)
		act_html(tb+'-lis', lib, 'tb=' +tb+ '&a=lis', false); 
	if (document.getElementById(tb+'-tot') != undefined)
		act_html(tb+'-tot', lib, 'tb=' +tb+ '&a=tot', false); 
	if (document.getElementById('indicador-indicador') != undefined)
		if (document.getElementById('grafica_gra') != undefined)
			graficar();
	if (parent.document.getElementById(tb+'-frm- ') != undefined)
		resizeIframe(parent.document.getElementById(tb+'-frm-con').childNodes[0]);
}

function act_html(a, b, c, d = false) {  
	if (document.getElementById(a) != undefined) {
		pajax(b, c+form_input('fapp'), function () { 
            var x=document.getElementById(a);
            if (x.tagName=="INPUT")
                x.value = this.responseText.replace(/(\r\n|\n|\r)/gm,"");
            else 
			    x.innerHTML = this.responseText.replace(/(\r\n|\n|\r)/gm,"");
			if (x.classList.contains('contenido')){
				var f=x.id.replace('con','foco');
				if (document.getElementById(f)!=undefined)
				   foco(document.getElementById(f).innerText);
			}
			if (d != false)
				d.apply('a');
		});
    }
}

function pajax(path, data, callback, method = "POST", headers = null) {    
	var req = new XMLHttpRequest();
    loader=document.getElementById('loader');
	if (loader != undefined) loader.style.display = 'block';
	req.onreadystatechange = function () {
		if (this.readyState === 4) {
			if (this.status === 200) {
				try {
					callback.apply(this);
				} catch (e) {
					console.log(e);
				}
			}
			if (loader != undefined) loader.style.display = 'none';
		}
	};
	req.open(method, path);
	req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	for (var i in headers) {
		req.setRequestHeader(i, headers[i]);
	}
	req.send(data);
}

function form_input(a) {
	var d = "";
	var frm = document.getElementById(a);
	for (i = 0; i < frm.elements.length; i++) {
		if (frm.elements[i].tagName = "select" && frm.elements[i].multiple) {
			var vl = [];
			for (var o = 0; o < frm.elements[i].options.length; o++) {
				if (frm.elements[i].options[o].selected) {
					vl.push("'"+frm.elements[i].options[o].value+"'");
				}
			}
			d += "&"+frm.elements[i].id+"="+vl.join(",");
		} else {
			d += "&"+frm.elements[i].id+"="+frm.elements[i].value.toString();
		}
	}
	return d;
}


function showFil(a){
	desplegar(a+'-fil');
	if (document.getElementById(a) != undefined) {
		var w=document.getElementById(a);
		if(w.classList.contains('col-8')){
			w.classList.replace('col-8','col');
		}else{
			w.classList.replace('col','col-8');
		}
	}
	const fix=document.getElementsByClassName('fix');
	if (fix!=undefined){
		for(i=0;i<fix.length;i++){
			if(fix[i].classList.contains('col-8')){
				fix[i].classList.replace('col-8','col');
			}else{
				fix[i].classList.replace('col','col-8');
			}
		}
	}
}

function changeSelect(a,b){
	if(b!=''){
		const x = document.getElementById(a);
		const z = document.getElementById(b);
		z.innerHTML="";
		if (window.XMLHttpRequest)
			xmlhttp = new XMLHttpRequest();
		else
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.onreadystatechange = function () {
			if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)){
				data =JSON.parse(xmlhttp.responseText);
				console.log(data)
			}}
				xmlhttp.open("POST", ruta_app,false);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send('a=opc&tb='+a+b+'&id='+x.value);
				//~ var rta =data;
				var data=Object.values(data);
				var opt = document.createElement('option');
				opt.text ='SELECCIONE';
				// opt.classList.add('alerta');
				opt.value='';
				z.add(opt);
				for(i=0;i<data.length;i++){
					var obj=Object.keys(data[i]);
					var opt = document.createElement('option');
					opt.text =data[i][obj[1]];
					opt.value=data[i][obj[0]];;
					z.add(opt);
				}
	}
}

function selectDepend(a,b,c){
	if(b!=''){
		const x = document.getElementById(a);
		const z = document.getElementById(b);
		z.innerHTML="";
		if (window.XMLHttpRequest)
			xmlhttp = new XMLHttpRequest();
		else
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.onreadystatechange = function () {
			if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)){
				data =JSON.parse(xmlhttp.responseText);
				console.log(data)
			}}
				xmlhttp.open("POST",c,false);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send('a=opc&tb='+a+b+'&id='+x.value);
				//~ var rta =data;
				var data=Object.values(data);
				var opt = document.createElement('option');
				opt.text ='SELECCIONE';
				// opt.classList.add('alerta');
				opt.value='';
				z.add(opt);
				for(i=0;i<data.length;i++){
					var obj=Object.keys(data[i]);
					var opt = document.createElement('option');
					opt.text =data[i][obj[1]];
					opt.value=data[i][obj[0]];;
					z.add(opt);
				}
	}
}

function getData(a, ev,i,blo,path=ruta_app) {
	if (ev.type == 'click') {
		var c = document.getElementById(a+'-pro-con');
		var cmp=c.querySelectorAll('.captura,.bloqueo')
		if (loader != undefined) loader.style.display = 'block';
			if (window.XMLHttpRequest)
				xmlhttp = new XMLHttpRequest();
			else
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				xmlhttp.onreadystatechange = function () {
				if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)){
					data =JSON.parse(xmlhttp.responseText);
					if (loader != undefined) loader.style.display = 'none';
						console.log(data)
					}
				}
				xmlhttp.open("POST",path,false);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send('a=get&tb='+a+'&id=' + i.id);
				var rta =data;
				var data=Object.values(rta);
				for (i=0;i<cmp.length;i++) {
					//~ if cmp[i]==27{
						cmp[i].value=data[i];
						if(cmp[i].type==='checkbox')cmp[i].checked=false;
							if (cmp[i].value=='SI' && cmp[i].type==='checkbox'){
								cmp[i].checked=true;
							}else if(cmp[i].value!='SI' && cmp[i].type==='checkbox'){
								cmp[i].value='NO';
							}
							for (x=0;x<blo.length;x++) {
								if(cmp[i].name==blo[x]) cmp[i].disabled = true;
							}
				} 
	}
}


/* +++++++++++++++++++++++++++++++++++++++++++++++++++++FECTH++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

function myFetch(b, c, d) {
    const loader = document.getElementById('loader');
	if (loader?.style) loader.style.display = 'block';
  
    return fetch(b, {
      method: 'POST',
      headers: {
        'Content-type': 'application/x-www-form-urlencoded'
      },
      body: c + form_input('fapp')
    })
      .then(response => {
        if (response.ok) {
          return response.text();
        } else {
          throw new Error(`Error ${response.status}: ${response.statusText}`);
			errors(`Error ${response.status} : ${response.statusText}`);
        }
      })
      .then(data => {
        if (loader?.style) loader.style.display = 'none';
        console.log(data);
		if(data.includes('Error')){
			typeErrors(data);
		  }else{
			document.getElementById(d+'-modal').innerHTML=data;
			document.getElementById(`${d}-image`).innerHTML = '<div class="icon-popup rtaok"></div>';
		  }
        
		openModal();
      })
      .catch(error => {
        console.error('Error:', error);
		errors(`Error: ${error}`);
      });
  }

  function typeErrors(rta){
	console.error(rta);
	switch (true) {
		case rta.includes('Duplicate entry'):
		  errors('El elemento a guardar ya existe');
		  break;
		case rta.includes('SQL syntax'):
		  errors('Error de Sintaxis en el SQL');
		  break;
		case rta.includes('Access denied for user'):
		  errors('Acceso Denegado, valida la cadena de conexión a la BD');
		  break;
		case rta.includes('Too many connections'):
		  errors('Ha alcanzado temporalmente el límite de conexiones, número alto de usuarios.');
		  break;
		case rta.includes('Out of memory'):
		  errors('No tiene suficiente memoria para almacenar el resultado completo');
		  break;
		case rta.includes('Unknown column'):
			errors('Error asociado a una columna inexistente de la tabla');
			break;
		case rta.includes('Table'):
		  errors('Error en la sintaxis, asociada a la tabla');
		  break;
		default:
			const err = rta.match(/msj\['(.*?)'\]/);
			if (err && err[1]) {
				warnin(err[1]);
			} else {
				errors('Error al realizar la tarea,intenta nuevamente');
			}
		  
	  }
  }

  /* async function myFetch(url, formData, id, method = 'POST') {
	const loader = document.getElementById('loader');
	if (loader?.style) loader.style.display = 'block';
  
	const options = {
	  method,
	  headers: {
		'Content-type': 'application/x-www-form-urlencoded'
	  },
	  body: new FormData(formData),
	};
  
	try {
	  const response = await fetch(url, options);
  
	  if (!response.ok) {
		throw new Error(`Error ${response.status}: ${response.statusText}`);
	  }
  
	  const data = await response.text();
	  console.log(data);
  
	  if (loader?.style) loader.style.display = 'none';
  
	  document.getElementById(`${id}-modal`).innerHTML = data;
	  if(data.contains('Error')){
		document.getElementById(`${id}-image`).innerHTML = '<svg class="icon-popup"><use xlink:href="#bad"/></svg>';
	  }else{
		document.getElementById(`${id}-image`).innerHTML = '<svg class="icon-popup"><use xlink:href="#ok"/></svg>';
	  }
	} catch (error) {
	  console.error('Error:', error);
  
	  if (loader?.style) loader.style.display = 'none';
  
	  document.getElementById(`${id}-modal`).innerHTML = `Error: ${error}`;
	  document.getElementById(`${id}-image`).innerHTML = '<svg class="icon-popup"><use xlink:href="#bad"/></svg>';
	}
  } */
  
  
  async function pFetch(path, data, callback, method = "POST", headers = {}) {
	try {
	  const loader = document.getElementById("loader");
	  if (loader) loader.style.display = "block";
  
	  const response = await fetch(path, {
		method,
		headers: {
		  "Content-type": "application/json",
		  ...headers,
		},
		body: JSON.stringify(data),
	  });
  
	  if (!response.ok) {
		throw new Error("Network response was not ok.");
		handleRequestError('Network response was not ok');
	  }
  
	  const responseData = await response.json();
  
	  if (callback) {
		callback(responseData);
	  }
  
	  return responseData;
	} catch (error) {
	  console.error("Error:", error);
	handleRequestError(error+response.text());
	} finally {
	  const loader = document.getElementById("loader");
	  if (loader) loader.style.display = "none";
	}
  }
  
  async function getJSON(action, table, id,url=ruta_app,customHeaders = {}) {
	if (loader?.style) loader.style.display = "block";
	const headers = {
	  "Content-type": "application/x-www-form-urlencoded",
	  ...customHeaders,
	};
	const body = `a=${action}&tb=${table}&id=${id}`;
	let rawData;
	try {
	  const response = await fetch(url, { method: "POST", headers, body });
	  if (!response.ok) {
		rawData = await response.text();
		throw new Error(`Network response was not ok: ${response.status} - ${response.statusText}`);
	  }
  
	  const rawData = await response.text(); // Obtén el contenido de la respuesta como texto
	  console.error(`Response: ${rawData}`);

	  const data = JSON.parse(rawData);
  
	  if (loader?.style) loader.style.display = "none";
	  return data;
	} catch (error) {
	  console.error(error+rawData);
	  	if (rawData) {
      		console.error(`Error Response: ${rawData}`);
    	}
	  handleRequestError(error.message);
	}
  }
  
  function handleRequestError(error) {
	if (loader?.style) loader.style.display = "none";
	console.error(error);
	errors("Error al realizar la solicitud");
  }
   
  
  function handleRequestError(error) {
	if (loader?.style) loader.style.display = 'none';
	console.error(error); // Cambia console.log por console.error
	errors('Error al realizar la solicitud');
  }

  
  function getDatForm(clsKey, fun,clsCmp) {
	const c = document.querySelectorAll(`.${clsKey} input, .${clsKey} select`);
	let id = '';
		for (let i = 0; i < c.length; i++) {
		  const {value} = c[i];
		  if (value === '') {
				break;
		  }
		  id += `${value}_`;
		}
		if (id===''){
		  return false;
		}else{
			id = id.slice(0, -1);
				getJSON('get', fun, id)
				  .then(data => {
					if (Object.keys(data).length === 0) {
						inform('No se encontraron registros asociados');
						return;
					  }
					  var data=Object.values(data);
					  var cmp=document.querySelectorAll(`.${clsCmp} input ,.${clsCmp} select`);
					  for (i=1;i<cmp.length;i++) {
						  if(cmp[i].type==='checkbox')cmp[i].checked=false;
							  if (cmp[i].value=='SI' && cmp[i].type==='checkbox'){
								  cmp[i].checked=true;
							  }else if(cmp[i].value!='SI' && cmp[i].type==='checkbox'){
								  cmp[i].value='NO';
							  }
							  // key += value !== '' ? value + '_' : '';
							  cmp[i].value=i==0?data[i-1]:data[i];
							  for (x=0;x<c.length;x++) {
								  if(cmp[i].name==c[x]) cmp[i].disabled = true;
							  }
					  }
				  })
			  .catch(handleRequestError);
	  }	
  }

  function getDaTab(clsKey, fun,clsCmp) {
	const c = document.querySelectorAll(`.${clsKey} input, .${clsKey} select`);
	let id = '';
		for (let i = 0; i < c.length; i++) {
		  const {value} = c[i];
		  if (value === '') {
				break;
		  }
		  id += `${value}_`;
		}
		if (id===''){
		  return false;
		}else{
			id = id.slice(0, -1);
				getJSON('get', fun, id)
				  .then(data => {
					if (Object.keys(data).length === 0) {
						inform('No se encontraron registros asociados');
						return;
					  }
					  var data=Object.values(data);
					  var cmp=document.querySelectorAll(`.${clsCmp} input ,.${clsCmp} select`);
					  for (i=1;i<cmp.length;i++) {
						  if(cmp[i].type==='checkbox')cmp[i].checked=false;
							  if (cmp[i].value=='SI' && cmp[i].type==='checkbox'){
								  cmp[i].checked=true;
							  }else if(cmp[i].value!='SI' && cmp[i].type==='checkbox'){
								  cmp[i].value='NO';
							  }
							  // key += value !== '' ? value + '_' : '';
							  cmp[i].value=i==0?data[i-1]:data[i];
							  for (x=0;x<c.length;x++) {
								  if(cmp[i].name==c[x]) cmp[i].disabled = true;
							  }
					  }
				  })
			  .catch(handleRequestError);
	  }	
  }

  function validDate(a,b,c){
	let Ini=dateAdd(b);
	let Fin=dateAdd(c);
	
	let min=`${Ini.a}-${Ini.m}-${Ini.d}`;
	let max=`${Fin.a}-${Fin.m}-${Fin.d}`;
	
	RangeDateTime(a.id,min,max);
  }
  
  	/* +++++++++++++++++++++++++++++++++++++++++++++SELECT MULTIPLE+++++++++++++++++++++++++++++++++++++++++++++++ */

	window.onmousedown = function (e) {
		let el = e.target;
		if (el.tagName.toLowerCase() == 'option' && el.parentNode.hasAttribute('multiple')) {
			e.preventDefault();
			selectedMultiple(el);
		}
	}


	
	function selectedMultiple(a){
		let out='';
		const mul=document.getElementById(a.parentNode.id).parentNode.childNodes[1];
		let selOpt=a.parentNode;
	
	
			if (a.hasAttribute('selected')) a.removeAttribute('selected');
			else a.setAttribute('selected', '');
	
			var sel = selOpt.cloneNode(true);
			selOpt.parentNode.replaceChild(sel, selOpt);
				
			if(sel.selectedIndex >=0){
				if(sel.options[sel.selectedIndex].value==""){
					if(sel.selectedOptions[0].text=='Todos'){
						sel.selectedOptions[0].text='Ninguno';
						sel.selectedOptions[0].removeAttribute('selected');
						selectedAll(document.getElementById(sel.id),true);
					}else{
						sel.selectedOptions[0].text='Todos';
						sel.selectedOptions[0].removeAttribute('selected');
						selectedAll(document.getElementById(sel.id),false);
					}
				}
			}          
			for (let i=0; i<sel.selectedOptions.length; i++) {
				out += sel.selectedOptions[i].label;
				if (sel.length-1==sel.selectedOptions.length) {
					  out='Selecciono Todos';
				}else if(sel.selectedOptions.length>3){
					  out='Selecciono '+sel.selectedOptions.length;
				}
				if (i === (sel.selectedOptions.length - 2)) {
				  out +=  ", ";
				} else if (i < (sel.selectedOptions.length - 2)) {
				  out += ", ";
				}
			  }
	  mul.value = out;
	  sel.focus();
	}
	
	function showMult(a,b){
		let x= 'f'+a.id
		let s=document.getElementById(x);
		if(s != null ){
			if (s.classList.contains('close') && b===true) {
				s.classList.remove('close');
				s.focus();
				let sHei= document.getElementById(a.id).clientWidth + 1 + "px";
				document.getElementById(x).style.width=sHei;
			} else {
				s.classList.add('close');
			}
		}else{
			if (a!=null)document.getElementById(a.id).classList.add('close');
		}
	}
	
	function searchMult(a){
		let selmul=a.nextElementSibling;
		var sel=document.getElementById(selmul.id);
	
		if (a.value==''){
			selectedAll(sel,false);
		}
	}
	
	function selectedAll(a,b=true){
		if (b!==true){
			for(var i=0;i<a.length;i++){
				if( a.options[i].hasAttribute('selected')){
					a.options[i].removeAttribute('selected');
				}
			}
		}else{
			for(var i=1;i<a.length;i++){
					a.options[i].setAttribute('selected','selected');
				}
			}
		}


//++++++++++++++++++++++++++++++++APARIENCIA++++++++++++++++++++++
function collapse(a) {
	const el=document.getElementById(a.id);
	if (el.classList.contains('collapsible')){
		var coll = document.getElementsByClassName('collapsible');
		var i;
		for (i = 0; i < coll.length; i++) {
    		coll[i].classList.toggle("active");
    		var content = coll[i].nextElementSibling;
    		if (content.style.maxHeight){
      			content.style.maxHeight = null;
    		} else {
      			content.style.maxHeight = content.scrollHeight + "px";
    		}
		}
	}else{
		return;
	}
}

function hideFix(a,b){
	const panel=document.getElementById(a+'-'+b);
	if (panel!=undefined) panel.style.display='none';
}

//++++++++++++++++++++++++++++++++Activar e Inactivar Elementos++++++++++++++++++++++
function enaFie(ele, flag) {
	if(ele.type==='checkbox' && ele.checked==true){
		ele.checked=false;
	}else if(ele.type==='checkbox'){
		ele.value = 'NO';
	}else{
		ele.value = '';
	}
    ele.disabled = flag;
    ele.required = !flag;
    ele.classList.toggle('valido', !flag);
    ele.classList.toggle('captura', !flag);
    ele.classList.toggle('bloqueo', flag);
    flag ? ele.setAttribute('readonly', true) : ele.removeAttribute('readonly');
}

function noRequired(ele,flag){
	ele.required = !flag;
	ele.classList.toggle('valido', !flag);
}


function hidFie(ele,flag){
	switch (ele.nodeName) {
		case 'SELECT':
			ele.required = !flag;
    		ele.classList.toggle('valido', !flag);
    		ele.classList.toggle('captura', !flag);
			ele.classList.toggle('oculto', flag);
			ele.classList.toggle('bloqueo', flag);
			if(!flag){
				ele.disabled = flag;
				ele.setAttribute('readonly', true); 
			}else{
				ele.disabled = !flag;
				ele.disabled = !flag;
				ele.removeAttribute('readonly')
			}
			if (flag==true)ele.value='';
			break;
		case 'INPUT':
			ele.required = !flag;
    		ele.classList.toggle('valido', !flag);
    		ele.classList.toggle('captura', !flag);
			ele.classList.toggle('oculto', flag);
			ele.classList.toggle('bloqueo', flag);
			if(!flag){
				ele.disabled = flag;
				ele.removeAttribute('readonly')
			}else{
				ele.disabled = !flag;
				ele.setAttribute('readonly', true); 
			}
			if (flag==true)ele.value='';
			break;
		case 'TEXTAREA':
			ele.required = !flag;
			ele.classList.toggle('valido', !flag);
			ele.classList.toggle('captura', !flag);
			ele.classList.toggle('oculto', flag);
			ele.classList.toggle('bloqueo', flag);
			if(!flag){
				ele.disabled = flag;
				ele.removeAttribute('readonly')
			}else{
				ele.disabled = !flag;
				ele.setAttribute('readonly', true); 
			}
			if (flag==true)ele.value='';
			break;
		case 'INPUT':
			ele.required = !flag;
    		ele.classList.toggle('valido', !flag);
    		ele.classList.toggle('captura', !flag);
			ele.classList.toggle('oculto', flag);
			ele.classList.toggle('bloqueo', flag);
			if(!flag){
				ele.removeAttribute('readonly')
				ele.setAttribute('readonly', true); 
			}else{
				ele.disabled = flag;
			}
			if (flag==true && ele.type=='checkbox') {
				ele.value='NO';
				ele.checked=!flag;
			}else if(flag==false && ele.type=='checkbox' && ele.checked==false){
				ele.value='NO';
			}
			if (flag==true && ele.type!='checkbox')ele.value='';
			break;
		default:
		ele.classList.toggle('oculto', flag);
			break;
	}
}

function lockeds(ele,flag) {
    ele.readOnly = flag === true;
    ele.classList.toggle('bloqueo', flag === true);
    ele.disabled = flag === true;
}


function hidLabFie(ele,flag){
	switch (ele.nodeName) {
		case 'SELECT':
			ele.required = !flag;
    		ele.classList.toggle('valido', !flag);
    		ele.classList.toggle('captura', !flag);
			ele.classList.toggle('oculto', flag);
			if (flag==true)ele.value='';
			break;
		case 'INPUT':
			ele.required = !flag;
    		ele.classList.toggle('valido', !flag);
    		ele.classList.toggle('captura', !flag);
			ele.classList.toggle('oculto', flag);
			if (flag==true && ele.type=='checkbox') {
				ele.value='NO';
				ele.checked=!flag;
			}else if(flag==false && ele.type=='checkbox' && ele.checked==false){
				ele.value='NO';
			}
			if (flag==true && ele.type!='checkbox')ele.value='';
			break;
		default:
		ele.classList.toggle('oculto', flag);
			break;
	}
}

  function Color(a) {
	var div = document.getElementById(a);
	var tabla = div.querySelector('table');
  
	tabla.addEventListener('click', function(event) {
	  var td = event.target.closest('td');
  
	  if (td !== null && td.parentElement !== null) {
		cambiarColorFila(div, td);//td.parentElement   .firstChild.parentNode
	  }
	});
  
	var filaSeleccionada = null;
  
	function cambiarColorFila(div, fila) {
	  if (filaSeleccionada !== null) {
		filaSeleccionada.style.backgroundColor = '';
	  }
  
	  fila.style.backgroundColor = '#fbeb4dc4';
	  filaSeleccionada = fila;
	}
  }

  
  
  
  
//++++++++++++++++++++++++++++++++Validar fechas Minimos y maximos++++++++++++++++++++++
function dateAdd(d=0,m=0,y=0,H=0,M=0,S=0){
	var now=new Date();
	now.setDate(now.getDate()+d)
	now.setMonth(now.getMonth() + m)
	now.setFullYear(now.getFullYear()+y);
	now.setHours(now.getHours()+H-5);
	now.setMinutes(now.getMinutes()+M);
	now.setSeconds(now.getSeconds()+S);
	
	let days= now.toISOString().slice(8,10);
	let mont= now.toISOString().slice(5,7);
	let year= now.toISOString().slice(0,4);
	let hour= now.toISOString().slice(11,13);
	let minu= now.toISOString().slice(14,16);
	let seco= now.toISOString().slice(17,19);
    
	return { 'd': days,
             'm': mont,
             'a': year,
             'H': hour,
             'M': minu,
             'S': seco
          };
}

function RangeDateTime(a,b,c){
	d = document.getElementById(a);
	d.min=b;
	d.max=c;
}

function calImc(a, b, i) {
	const pe = document.getElementById(a);
	const ta = document.getElementById(b.id);
	const imc = document.getElementById(i);
  
	if (ta && pe) {
	  const tallaMetros = ta.value / 100;
	  const calImc = pe.value / Math.pow(tallaMetros,2);
	  imc.value = calImc.toFixed(2);
	}
  }

  async function DownloadCsv(a,b,c) {
	try {
		const data = await getJSON(a,b,form_input(c));
		csv(data['file']);
	} catch (error) {
	  console.error(error);
	  errors('ya se realizo la descarga el dia de hoy intentalo mañana nuevamente.');
	}
  }

  function uploadCsv(ncol, tab, archivo, ruta, mod) {
	if (archivo.files.length > 0) {
	  const loader = document.getElementById('loader');
	  if (loader != undefined) loader.style.display = 'block';
  
	  const formData = new FormData();
	  formData.append("ncol", ncol);
	  formData.append("tab", tab);
	  formData.append("archivo", archivo.files[0]);
  
	  let data = null; // Inicializa la variable data
  
	  fetch(ruta, {
		method: "POST",
		body: formData,
	  })
		.then((response) => {
		  if (response.ok) {
			return response.text();
		  } else {
			if (loader != undefined) loader.style.display = 'none';
			throw new Error("Network response was not ok");
		  }
		})
		.then((responseData) => {
		  data = responseData; // Almacena la respuesta en la variable data
		  const response = JSON.parse(data);
		  const type = response.type;
		  const msj = response.msj;
		  if (loader != undefined) loader.style.display = 'none';
		  if (type == 'Error') {
			errors(msj);
		  } else if (type == 'OK') {
			ok(msj);
		  } else {
			warnin(msj);
		  }
		  act_lista(mod);
		})
		.catch((error) => {
		  console.error(error + '=' + data); // Muestra el valor de data junto con el error
		  errors('Ha ocurrido un error al procesar la solicitud');
		});
	} else {
	  warnin('Selecciona un archivo válido');
	}
  }
  

  function hidFieOpt(act,clsCmp,x,valid) {
	const cmpAct=document.getElementById(act);
	const cmps = document.querySelectorAll(`.${clsCmp}`);
	if(cmpAct.value=='SI'){
		for(i=0;i<cmps.length;i++){
			hidFie(cmps[i],!valid);
		}
	}else{
		for(i=0;i<cmps.length;i++){
			hidFie(cmps[i],valid);
		}
	}
}


/* 	const navToggle = document.querySelector(".nav-toggle");
const navMenu = document.querySelector(".nav-menu");

navToggle.addEventListener("click", () => {
  navMenu.classList.toggle("nav-menu_visible");

  if (navMenu.classList.contains("nav-menu_visible")) {
    navToggle.setAttribute("aria-label", "Cerrar menú");
  } else {
    navToggle.setAttribute("aria-label", "Abrir menú");
  }
});
 */


/* const all = document.querySelector('body');
const thm = document.getElementById('theme');

if (localStorage.getItem('demo-theme')) {
  const theme = localStorage.getItem('demo-theme');
  all.classList.add(`theme-${theme}`);
}

  thm.addEventListener('change', e => {
    let colour = thm.value;
    all.className = '';
    all.classList.add(`theme-${colour}`);
    localStorage.setItem('demo-theme', colour);
  }); */

 
