var kunden, produkte, debug;


function init(){
	debug=0;
	kunden=document.getElementById("Kunden");
	produkte=document.getElementById("Produkte");
	if(debug){alert("init!");}
}

function reset(){
	kunden.style.display="none";
	produkte.style.display="none";
	if(debug){alert("reset!");}

}

function menu_kunden(){
	reset();
	kunden.style.display="block";
	if(debug){alert(kunden.style.display+"Kunden!");}
}

function menu_produkte(){
	reset();
	produkte.style.display="block";
	if(debug){alert("Produkte!");}
}

function changecolor(elementname, farbe){
	element=document.getElementById(elementname);
	element.style.backgroundColor = farbe;
	if(debug){alert(elementname+" "+farbe);}
}

