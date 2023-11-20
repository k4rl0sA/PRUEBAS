function hideCuida(act,clsCmp) {
	const cmpAct=document.getElementById(act);
	const cmps = document.querySelectorAll(`select.${clsCmp}, input.${clsCmp}`);
	if(cmpAct.value=='SI'){
		for(i=0;i<cmps.length;i++){
			enaFie(cmps[i],false);
		}
	}else{
		for(i=0;i<cmps.length;i++){
			enaFie(cmps[i],true);
		}
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


function valSist(a){
	const sis=document.getElementById(a).value;
	if(parseInt(sis)<60 || parseInt(sis)>310){
	warnin('El Valor ingresado en la tension Arterial Sistolica, no cumple con el rango establecido');
	return true;
	}else{
		return false;
	}
}

function valDist(a){
	const dis=document.getElementById(a).value;
	if(parseInt(dis)<40 || parseInt(dis)>185){
	warnin('El Valor ingresado en la tension Arterial Diastolica, no cumple con el rango establecido');
	return true;
	}else{
		return false;
	}
}

function valGluco(a){
	const glu=document.getElementById(a).value;
	if(parseInt(glu)<5 || parseInt(glu)>600){
	warnin('El Valor ingresado en la Glucometria, no cumple con el rango establecido');
	return true;
	}else{
		return false;
	}
}

function valGluc(a){
	const glu=document.getElementById(a);
	if (glu!==null){
	const al1=document.getElementById('cronico').value;
	const ges=document.getElementById('gestante').value;
		if(al1=='1' || ges=='1'){
			enaFie(glu,false);
		}else{
			enaFie(glu,true);
		}
	}
}

function valTalla(a){
	const tal=document.getElementById(a).value;
	if(parseInt(tal)<20 || parseInt(tal)>210){
	warnin('El Valor ingresado en la Talla, no cumple con el rango establecido');
	return true;
	}else{
		return false;
	}
}
function valPeso(a){
	const pes=document.getElementById(a).value;
	if(parseInt(pes)<0.50 || parseInt(pes)>150){
	warnin('El Valor ingresado en el Peso, no cumple con el rango establecido');
	return true;
	}else{
		return false;
	}
}

  function getAge(a) {
	const born = document.getElementById(a);
	const dateBorn = new Date(born.value);
	const now = new Date();
  
	const milSeg = now - dateBorn;
	const age = new Date(milSeg);
  
	const años = age.getUTCFullYear() - 1970;
	const meses = age.getUTCMonth();
	const dias = age.getUTCDate() - 1;
  
	// resultado.textContent = `Edad: ${años} años, ${meses} meses, ${dias} días`;
	return {
		anios: años,
		meses: meses,
		dias: dias
	}

	console.log(anios);
  }
  
function DisableUpdate(act,clsCmp){
	const ele = document.querySelectorAll('select.'+clsCmp+',input.'+clsCmp+',#'+act);
	for (i = 0; i < ele.length; i++) {
				enaFie(ele[i],true);
	}
}

function disabledCmp(clsCmp){
	const ele = document.querySelectorAll('select.'+clsCmp+',input.'+clsCmp);
	for (i = 0; i < ele.length; i++){ 
				enaFie(ele[i],true);
	}
}



/* function enabEtni(etniaSelector, ocuCls, idiCls) {
	const ele = document.querySelectorAll(`select.${ocuCls}, input.${ocuCls}`);
	const idi = document.querySelectorAll(`select.${idiCls}, input.${idiCls}`);
	const act = document.getElementById(etniaSelector);
  
	const bloquearCampos = (bloquear) => {
	  for (let j = 0; j < ele.length; j++) {
		if (ele[j].classList.contains(ocuCls)) {
		  enaFie(ele[j], bloquear);
		}
	  }
	};
  
	if (act.value === '1' || act.value === '3') {
	  // Ninguno o Afro
	  bloquearCampos(true);
	  // Bloquea el campo de idioma en los casos de etnia '1' o '3'
	  for (let j = 0; j < idi.length; j++) {
		enaFie(idi[j], true);
	  }
	} else if (act.value === '2') {
	  // Indígena
	  for (let j = 0; j < ele.length; j++) {
		enaFie(ele[j], false);
	  }
	  // Desbloquea el campo de idioma en el caso de etnia '2' (Indígena)
	  for (let j = 0; j < idi.length; j++) {
		enaFie(idi[j], false);
	  }
	} else {
	  // Otros casos
	  bloquearCampos(true);
	  // Bloquea el campo de idioma en otros casos
	  for (let j = 0; j < idi.length; j++) {
		enaFie(idi[j], false);
	  }
	}
  } */
  
  function enabEtni(a, clsCmp, i) {
	const ele = document.querySelectorAll(`select.${clsCmp}, input.${clsCmp}`);
	const idi = document.querySelectorAll(`select.${i}, input.${i}`);
	const act = document.getElementById(a);
	const bloquearCampos = (bloquear) => {
	  for (let j = 0; j < ele.length; j++) {
		if (ele[j].classList.contains(clsCmp)) {
		  enaFie(ele[j], bloquear);
		}
	  }
	};
	const bloquearIdioma = (bloquear) => {
	  for (let j = 0; j < idi.length; j++) {
		enaFie(idi[j], bloquear);
	  }
	};
	bloquearCampos(true); 
	if (act.value === '2') {
	  bloquearCampos(false);
	}
	if (act.value !== '1' && act.value !== '3') {
	  bloquearIdioma(false);
	}
  }
	
		
  
  
	
  
  

  
function enbValue(a,clsCmp,v){
	const ele = document.querySelectorAll('select.'+clsCmp+',input.'+clsCmp);
	const act=document.getElementById(a);
	if (act.value==v){
		for (i = 0; i < ele.length; i++){ 
			enaFie(ele[i],false);
		}
	}else{
		for (i = 0; i < ele.length; i++){ 
			enaFie(ele[i],true);
		}	
	}
}

 function enbValsCls(a, ClsCmp) {
	const act = document.getElementById(a);
	const numValue = parseInt(act.value, 10);
  
	ClsCmp.forEach(cls => {
	  const elementsToDisable = document.querySelectorAll(`select.${cls}, input.${cls}`);
	  elementsToDisable.forEach(element => {
		enaFie(element, true); 
	  });
	});

	let adjustedIndex = numValue - 1;
  
	if (!isNaN(adjustedIndex) && adjustedIndex >= 0 && adjustedIndex < ClsCmp.length) {
	  const clsToEnable = ClsCmp[adjustedIndex];
	  const elementsToEnable = document.querySelectorAll(`select.${clsToEnable}, input.${clsToEnable}`);
  
	  elementsToEnable.forEach(element => {
		enaFie(element, false);
	  });
	}
  }
 	


function enabEapb(a,clsCmp){
	const ele = document.querySelectorAll('select.'+clsCmp+',input.'+clsCmp);
	const act=document.getElementById(a);
	if (act.value!='5'){
		for (i = 0; i < ele.length; i++){ 
				enaFie(ele[i],false);
		}
	}else{
		for (i = 0; i < ele.length; i++){ 
			enaFie(ele[i],true);
		}	
	}
}

function enabAfil(a,clsCmp){
	const ele = document.querySelectorAll('select.'+clsCmp+',input.'+clsCmp);
	const act=document.getElementById(a);
	if (act.value=='5'){
		for (i = 0; i < ele.length; i++){ 
				enaFie(ele[i],false);
		}
	}else{
		for (i = 0; i < ele.length; i++){ 
			enaFie(ele[i],true);
		}	
	}
}

function enabFielSele(a, b, c, d) {
	for (i = 0; i < c.length; i++) {
    	var ele = document.getElementById(c[i]);
    	enaFie(ele, !d.includes(a.value) || !b);
  	}
}

function addupd(act,clsCmp,b){
	const ele = document.querySelectorAll('select.'+clsCmp+',input.'+clsCmp);
	const selectedDate = Date.parse(act.value);
	if (act.value!='') {
		if (isNaN(selectedDate)) {
			for (i = 0; i < ele.length; i++) {
				enaFie(ele[i], true);
			}
  		} else {
  			for (i = 0; i < ele.length; i++) {
				enaFie(ele[i], false);
			}
  		}	
	}else{
		for (i = 0; i < ele.length; i++) {
			enaFie(ele[i], true);
		}
	}
}



function enableDog(a,b){
	const ele = document.querySelectorAll('input.'+b);
	for (i=0; i<ele.length;i++) {
		if(a.value=='SI'){
			enaFie(ele[i],false);
  		}else{
			enaFie(ele[i],true);
		}
	}
}

function enableCat(a,b){
	const ele = document.querySelectorAll('input.'+b);
	for (i=0; i<ele.length;i++) {
		if(a.value=='SI'){
			enaFie(ele[i],false);
  		}else{
			enaFie(ele[i],true);
		}
	}
}

function enabLoca(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	const act=document.getElementById(a);
	for (i=0; i<ele.length;i++) {
		if(act.value=='SI'){
			enaFie(ele[i],true);
  		}else{
			enaFie(ele[i],false);
		}
	}
}
// periAbd('gestante','AbD',1)
function periAbd(a,b,c){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	const act=document.getElementById(a);
	for (i=0; i<ele.length;i++) {
		if(act.value=='SI' ){
			enaFie(ele[i],true);
		}else if(c!==true){
			enaFie(ele[i],true);
  		}else{
			enaFie(ele[i],false);
		}
	}
}



function timeDesem(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	for (i=0; i<ele.length;i++) {
		if(a.value==5){
			enaFie(ele[i],false);
  		}else{
			enaFie(ele[i],true);
		}
	}
}

function disaLoca(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	const act=document.getElementById(a);
	for (i=0; i<ele.length;i++) {
		if(act.value=='SI'){
			enaFie(ele[i],true);
  		}else{
			enaFie(ele[i],false);
		}
	}
}

function tipVivi(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	const act=document.getElementById(a);
	for (i=0; i<ele.length;i++) {
		if(act.value=='1' || act.value=='2'){
			enaFie(ele[i],false);
  		}else{
			enaFie(ele[i],true);
		}
	}
}

function enabOthNo(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	const act=document.getElementById(a);
	for (i=0; i<ele.length;i++) {
		if(act.value=='2'){
			enaFie(ele[i],true);
  		}else{
			enaFie(ele[i],false);
		}
	}
}

function disaOthNo(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	const act=document.getElementById(a);
	for (i=0; i<ele.length;i++) {
		if(act.value=='2'){
			enaFie(ele[i],false);
  		}else{
			enaFie(ele[i],true);
		}
	}
}

function enabOthSi(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	const act=document.getElementById(a);
	for (i=0; i<ele.length;i++) {
		if(act.value=='1'){
			enaFie(ele[i],false);
  		}else{
			enaFie(ele[i],true);
		}
	}
}

function enabYes(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	const act=document.getElementById(a);
	for (i=0; i<ele.length;i++) {
		if(act.value=='SI'){
			enaFie(ele[i],false);
  		}else{
			enaFie(ele[i],true);
		}
	}
}
// Al menos un elemento tiene valor SI
function min1ElmSi(a,ClsCmp) {
	const ele = document.querySelectorAll('select.'+ClsCmp+',input.'+ClsCmp+',textarea.'+ClsCmp);
	for (const elm of ele) {
	  if (elm.value === '1') {
		return true; 
	  }
	}
	return false;
  }
  

function enabAlert(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	for (i=0; i<ele.length;i++) {
		if(a.value=='NO'){
			enaFie(ele[i],true);
  		}else{
			enaFie(ele[i],false);
		}
	}
}

function ValTensions(a,b){
	const sis=document.getElementById(a).value;
	const dis=b.value;
	if(sis!='' && dis!=''){
		if(Math.floor(dis)>Math.floor(sis)){
			return inform('Recuerde que el valor de la tensión arterial diastolica, no puede ser mayor a la tensión arterial sistolica');
		}
	}
}

function enabDesEsc(a,clsCmp,e){
	const edad=getAge(e.id);
	const ele = document.querySelectorAll('select.'+clsCmp+',input.'+clsCmp);
	const act=document.getElementById(a);
	if((edad['anios']<5 || edad['anios']>17) && act.value=='13'){
		act.value='';
	}else{
		if (act.value=='13'){
			for (i = 0; i < ele.length; i++){ 
					enaFie(ele[i],false);
			}
		}else{
			for (i = 0; i < ele.length; i++){ 
				enaFie(ele[i],true);
			}	
		}
	}
}

/* function EnabEfec(a, b) {
	const clas = b.map(clase => `select.${clase}, input.${clase},textarea.${clase}`).join(', ');
	elems = [...document.querySelectorAll(clas)];
  
	elems.forEach(element => {
	  const flag = (a.value !== '1');
	  enaFie(element, flag);
	});
  } */

  function child14(a,b){
	rta =getAge(a);
	ano=rta['anios'];
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	for (i=0; i<ele.length;i++) {
		if(ano<14){
			enaFie(ele[i],true);
  		}else{
			enaFie(ele[i],false);
		}
	}
}

function Ocup5(a,b){
	rta =getAge(a);
	ano=rta['anios'];
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	for (i=0; i<ele.length;i++) {
		if(ano<6){
			enaFie(ele[i],true);
  		}else{
			enaFie(ele[i],false);
		}
	}
}

function staEfe(a,b){
	const act=document.getElementById(a);
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	for (i=0; i<ele.length;i++) {
		if(act.value=='1'){
			enaFie(ele[i],true);
			ele[i].value='1';
  		}else{
			enaFie(ele[i],false);
			ele[i].value='';
		}
	}
}

function enabEtap(a, b) {
	const act = document.getElementById(a);
  
	const selector = b.map(clase => `select.${clase}, input.${clase}, textarea.${clase}`).join(', ');
  
	const elementos = [...document.querySelectorAll(selector)];
  
	elementos.forEach(elemento => {
	  const valorA = parseInt(act.value);
	  let habilitar = false;
  
	  switch (valorA) {
		case 1:
			habilitar = elemento.classList.contains('PuE');
		  break;
		case 2:
			habilitar = elemento.classList.contains('pRe');
		  break;
		case 3:
			habilitar = elemento.classList.contains('pRe');
		  break;
	  }
  
	  enaFie(elemento, habilitar);
	});
  }


  function enabClasValu(a, b) {
    const act = document.getElementById(a);
    const selector = b.map(clase => `select.${clase}, input.${clase}, textarea.${clase}`).join(', ');
    const elementos = [...document.querySelectorAll(selector)];
    elementos.forEach(elemento => {
        const valorA = parseInt(act.value);
        let bloquea = true; 

        switch (valorA) {
            case 1:
                if (elemento.classList.contains('mOr') || elemento.classList.contains('NOm')) {
                    bloquea = false;
                }
                break;
            case 2:
                if (elemento.classList.contains('mOr')) {
                    bloquea = false;
                } else {
                    bloquea = true;
                }
                break;
            default:
                if (elemento.classList.contains('mOr') || elemento.classList.contains('NOm')) {
                    bloquea = true;
                }
                break;
        }
        enaFie(elemento, bloquea);
    });
}

/* 
function enClSe(act, clin, claf) {
    const ac = document.getElementById(act);
    const els = [...document.querySelectorAll('.' + clin)];
    const valor = parseInt(ac.value);

    els.forEach(elm => {
        const clases = claf[valor - 1].map(clase => [`select.${clase}`, `input.${clase}`, `textarea.${clase}`]).flat();
        const bloquea = clases.some(clase => elm.classList.contains(clase));
        enaFie(els,bloquea);
    });
}
 */


function enClSe(act, clin, claf) {
    const ac = document.getElementById(act);
    const els = [...document.querySelectorAll(`select.${clin}, input.${clin}, textarea.${clin}`)];
    const valor = parseInt(ac.value);

    els.forEach(elm => {
        const index = Math.min(valor - 1, claf.length - 1);
        const clase = claf[index][0];

        const bloquea = elm.classList.contains(clase);
        enaFie(elm,!bloquea);
    });
}

//enClSe('accion', 'tOL', [['mOr'], ['NOm'], ['ANr']]);


function weksEtap(a,b){
	const act = document.getElementById(a);
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
		if(act.value=='3'){
			enaFie(ele[0],true);
			ele[0].value='43';
  		}else{
			enaFie(ele[0],false);
		}
  }


function Zsco(a,b='../vivienda/medidas.php'){
    // doc=a.split('_');
	const glu=document.getElementById(a);
	if (glu!==null){
	const pes=document.getElementById('peso').value;
	const fec=document.getElementById('fechanacimiento').value;
	const sex=document.getElementById('sexo').value;
	const tal=document.getElementById('talla').value;

	if (loader !== undefined) loader.style.display = 'block';
		if (window.XMLHttpRequest)
			xmlhttp = new XMLHttpRequest();
		else
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.onreadystatechange = function () {
			if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)){
				data =xmlhttp.responseText;
				if (loader != undefined) loader.style.display = 'none';
					console.log(data)
			}}
			xmlhttp.open("POST",b,false);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send('a=get&tb=zscore&val='+pes+'_'+fec+'_'+sex+'_'+tal);
			var rta =data;
			if(b=='../vivienda/medidas.php'){
				glu.value=JSON.parse(rta);
			}else{
				val=JSON.parse(rta);
				document.getElementById('clasi_nutri').value=val[1];
				glu.value=val[0];
			}
			
		}
}

/* async function searchUsu(a,b,c){
	try {
		const id=a.value;
		const rta = await getJSON('opc','usuario',id);
	} catch (error) {
		errors();
		console.error("Error al ejecutar la función", error);
	}
} */



  async function searchUsu(a) {
	try {
	  const info = a.value;
	  console.log("Datos a enviar al servidor:", info);
  
	  const data = await getJSON("opc", "usuario", info);
	  console.log("Datos recibidos del servidor:", data);
  
	  // Resto del código...
	} catch (error) {
	  console.error(error);
	  errors("No se pudo realizar la Busqueda.");	}
  }
  
  

  function handleResponse(responseData) {
	const resultadoElement = document.getElementById("resultado");
	const errorMessageElement = document.getElementById("error-message");
	if (responseData && responseData.sector_catastral) {
	  ok(`Sector Catastral: ${responseData.sector_catastral}, NumManzana: ${responseData.nummanzana}, Predio Num: ${responseData.predio_num}, Unidad Habit: ${responseData.unidad_habit}`);
	} else {
		warnin("No se encontraron resultados.");
	}
  }


function hiddxTamiz(a, b,e) {
	const cmpAct = document.getElementById(a);
	const cmps = document.querySelectorAll(`.${b}`);
	const edad = parseInt(cmpAct.value) > e;
  
	for (let i = 0; i < cmps.length; i++) {
		hidFie(cmps[i], true);
	  }
	for (let i = 0; i < cmps.length; i++) {
	  hidFie(cmps[i], !edad);
	}
  }
  
  function TamizxApgar(a) {
	const cmpAct = document.getElementById(a);
	const men = document.querySelectorAll('.cuestionario1');
	const may = document.querySelectorAll('.cuestionario2');
	const edad = parseInt(cmpAct.value);
  
	for (let i = 0; i < men.length; i++) {
	  hidFie(men[i], true);
	}
	for (let i = 0; i < may.length; i++) {
	  hidFie(may[i], true);
	}
	if (edad > 17) {
	  for (let i = 0; i < may.length; i++) {
		hidFie(may[i], false);
	  }
	} else if (edad > 6 && edad < 18) {
	  for (let i = 0; i < men.length; i++) {
		hidFie(men[i], false);
	  }
	}
  }
  



function ZscoAte(a){
	const sco=document.getElementById('dxnutricional');
	const anos=getAge('fecha_nacimiento');
	if (sco!==null || anos['anios']>4){
	const pes=document.getElementById('atencion_peso').value;
	const fec=document.getElementById('fecha_nacimiento').value;
	const sex=document.getElementById('sexo').value;
	const tal=document.getElementById('atencion_talla').value;

	if (loader !== undefined) loader.style.display = 'block';
		if (window.XMLHttpRequest)
			xmlhttp = new XMLHttpRequest();
		else
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.onreadystatechange = function () {
			if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)){
				data =xmlhttp.responseText;
				if (loader != undefined) loader.style.display = 'none';
					console.log(data)
			}}
			xmlhttp.open("POST",'lib.php',false);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send('a=get&tb=zscore&val='+pes+'_'+fec+'_'+sex+'_'+tal);
			var rta =data;
			sco.value=JSON.parse(rta);
	}
}

function EnabEfec(a,b,c,d,e) {
	const clas = b.map(clase => `select.${clase}, input.${clase},textarea.${clase}`).join(', ');
	const cls = c.map(clase => `select.${clase}, input.${clase},textarea.${clase}`).join(', ');
	const cla = d.map(clase => `select.${clase}, input.${clase},textarea.${clase}`).join(', ');
	const cl = e.map(clase => `select.${clase}, input.${clase},textarea.${clase}`).join(', ');

	elems = [...document.querySelectorAll(clas)];
	el = [...document.querySelectorAll(cla)];
	ele = [...document.querySelectorAll(cls)];
	elm = [...document.querySelectorAll(cl)];

	elems.forEach(element => {
		const flag = (a.value !== '1');
		enaFie(element, flag);
	});

	//no obligatorio
	el.forEach(elm => {
		const flag = true;
		if(elm.classList.contains('nO')){
			noRequired(elm, flag);
		}
	});

	//obligatorio
		ele.forEach(el => {
			const flag = false;
			if(el.classList.contains('Ob')){
				enaFie(el,flag);
			}
		});

	//bloqueados
	elm.forEach(elms => {
		const flag = true;
		if(elms.classList.contains('bL')){
			lockeds(elms,flag);
		}
	});

	if(a.value === '1'){
		enaFie(document.getElementById('condi_diag'),false);
	}else{
		enaFie(document.getElementById('condi_diag'),true);
	}
	
}

function EnabCron(a,b,c,d,e) {
	const clas = b.map(clase => `select.${clase}, input.${clase},textarea.${clase}`).join(', ');
	const cls = c.map(clase => `select.${clase}, input.${clase},textarea.${clase}`).join(', ');
	const cla = d.map(clase => `select.${clase}, input.${clase},textarea.${clase}`).join(', ');
	const cl = e.map(clase => `select.${clase}, input.${clase},textarea.${clase}`).join(', ');

	elems = [...document.querySelectorAll(clas)];
	el = [...document.querySelectorAll(cla)];
	ele = [...document.querySelectorAll(cls)];
	elm = [...document.querySelectorAll(cl)];

	if(a.value!=1){

	elems.forEach(element => {
		const flag = (a.value !== '1');
		enaFie(element, flag);
	});

	//no obligatorio
	el.forEach(elm => {
		const flag = true;
		if(elm.classList.contains('nO')){
			noRequired(elm, flag);
		}
	});

	//obligatorio
		ele.forEach(el => {
			const flag = false;
			if(el.classList.contains('Ob')){
				enaFie(el,flag);
			}
		});

	//bloqueados
	elm.forEach(elms => {
		const flag = true;
		if(elms.classList.contains('bL')){
			lockeds(elms,flag);
		}
	});
		enaFie(a,false);
		a.value=2;	
	}else{
		elems.forEach(element => {
			const flag = (a.value === '1');
			enaFie(element, flag);
		});
	
		//no obligatorio
		el.forEach(elm => {
			const flag = true;
			if(elm.classList.contains('nO')){
				noRequired(elm, flag);
			}
		});
	
		//obligatorio
			ele.forEach(el => {
				const flag = false;
				if(el.classList.contains('Ob')){
					enaFie(el,flag);
				}
			});
	
		//bloqueados
		elm.forEach(elms => {
			const flag = true;
			if(elms.classList.contains('bL')){
				lockeds(elms,flag);
			}
		});

		enaFie(a,false);
		a.value=1;
	}
}



function enabRuta(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b+',textarea.'+b);
	for (i=0; i<ele.length;i++) {
		if(a.value=='1'){
			enaFie(ele[i],false);
  		}else{
			enaFie(ele[i],true);
		}
	}
}

function enabCovid(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b+',textarea.'+b);
	for (i=0; i<ele.length;i++) {
		if(a.value=='1'){
			enaFie(ele[i],false);
  		}else{
			enaFie(ele[i],true);
		}
	}
}

function enabFincas(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b+',textarea.'+b);
	for (i=0; i<ele.length;i++) {
		if(a.value=='1'){
			enaFie(ele[i],false);
  		}else{
			enaFie(ele[i],true);
		}
	}
}


function stateVisit(a, b,c) {
	const clas = b.map(clase => `select.${clase}, input.${clase},textarea.${clase}`).join(', ');
	const cls = c.map(clase => `select.${clase}, input.${clase},textarea.${clase}`).join(', ');

	elems = [...document.querySelectorAll(clas)];
	ele = [...document.querySelectorAll(cls)];

	elems.forEach(element => {
	  const flag = (a.value !== '5');
	  enaFie(element, flag);
	});

	ele.forEach(el => {
		const flag = true;
		if(el.classList.contains('ne')){
			noRequired(el, flag);
		}else{
			enaFie(el,flag);
		}
		
	  });
  }

  

