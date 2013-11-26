//set up global variables to be used throughout the scripting.
var url="./sendmessage.php";
var url2="./sessionlog_return.php";
var formDiv,form;
var formVals=visitData=[];
var text,call,email=false;

//When initial page completes store the visit in the database and return the vistor info incase the user submits a comment.
$(document).ready(function(){
	$.post(url2,"none=none",function(data,textStatus,jqXHR)
    {
		if(data){visitData[0]=data.getElementsByTagName("servtime")[0].firstChild.nodeValue;visitData[1]=data.getElementsByTagName("ip")[0].firstChild.nodeValue;visitData[2]=data.getElementsByTagName("session")[0].firstChild.nodeValue;visitData[3]=data.getElementsByTagName("ref")[0].firstChild.nodeValue;visitData[4]=data.getElementsByTagName("agent")[0].firstChild.nodeValue;}
		else{visitData[0]=0;visitData[1]="10.10.10.10";visitData[2]=visitData[3]=visitData[4]="empty";}
	}).fail(function(jqXHR, textStatus, errorThrown){visitData[0]=0;visitData[1]="10.10.10.10";visitData[2]=visitData[3]=visitData[4]="empty";});
});

function createForm(a,b,c){
	if(document.getElementById("contactForm")){removeExisting(formDiv,document.getElementById("contactForm"));}
	formDiv=document.getElementById(c);
	form=document.createElement("form");
	form.setAttribute("id","contactForm");
	form.setAttribute("method","post");
	form.setAttribute("action","javascript:submit()");
	var radios=createRadio(a,c);
	if(b=='browser'){form.appendChild(radios);}
	if(text||call||email){form.appendChild(createContactMethod(a,b));form.appendChild(createActionButtons());}
	formDiv.appendChild(form);
}
function createRadio(a,c){
	var radioDiv=document.createElement("div");
	radioDiv.setAttribute("id","contactType");
	var radioP=document.createElement("h3");
	radioP.innerHTML="Contact Method";
	if(a=="text"){text=true;}else if(a=="email"){email=true;}else if(a=="call"){call=true;}else{radioP.innerHTML="Please select a Contact Method";}
	radioDiv.appendChild(radioP);
	var textLabel=document.createElement("label");
	textLabel.setAttribute("for","text");
	textLabel.setAttribute("class","radioLabel");
	textLabel.innerHTML="Text Message";
	var textRadio=document.createElement("input");
	textRadio.setAttribute("type","radio");
	textRadio.setAttribute("name","contact");
	textRadio.setAttribute("id","text");
	textRadio.setAttribute("value","text");
	textRadio.setAttribute("class","contact");
	textRadio.setAttribute("onchange","createContactMethod('text','browser','"+c+"');");
	if(text){textRadio.setAttribute("checked",true);}
	radioDiv.appendChild(textLabel);radioDiv.appendChild(textRadio);
	var emailLabel=document.createElement("label");
	emailLabel.setAttribute("for","email");
	emailLabel.setAttribute("class","radioLabel");
	emailLabel.innerHTML="Electronic Mail";
	var emailRadio=document.createElement("input");
	emailRadio.setAttribute("type","radio");
	emailRadio.setAttribute("name","contact");
	emailRadio.setAttribute("id","email");
	emailRadio.setAttribute("value","email");
	emailRadio.setAttribute("onclick","createContactMethod('email','browser','"+c+"');");
	emailRadio.setAttribute("class","contact");
	if(email){emailRadio.setAttribute("checked",true);}
	radioDiv.appendChild(emailLabel);radioDiv.appendChild(emailRadio);
	var callLabel=document.createElement("label");
	callLabel.setAttribute("for","call");
	callLabel.setAttribute("class","radioLabel");
	callLabel.innerHTML="Phone Call";
	var callRadio=document.createElement("input");
	callRadio.setAttribute("type","radio");
	callRadio.setAttribute("name","contact");
	callRadio.setAttribute("id","call");
	callRadio.setAttribute("value","call");
	callRadio.setAttribute("onchange","createContactMethod('call','browser','"+c+"');");
	callRadio.setAttribute("class","contact");
	if(call){callRadio.setAttribute("checked",true);}
	radioDiv.appendChild(callLabel);radioDiv.appendChild(callRadio);
	if(text||email||call){textRadio.setAttribute("disabled",true);emailRadio.setAttribute("disabled",true);callRadio.setAttribute("disabled",true);}
	return radioDiv;
}
function createContactMethod(a,b){
	if(document.getElementById("contactMethod")){removeExisting(form,document.getElementById("contactMethod"));}
	var cmDiv=document.createElement("div");
	cmDiv.setAttribute("id","contactMethod");
	var snLabel=document.createElement("label");
	snLabel.setAttribute("for","subName");
	snLabel.setAttribute("class","textLabel");
	snLabel.innerHTML="Your Name:";
	var subTime=document.createElement("input");
	subTime.setAttribute("name","subTime");
	subTime.setAttribute("value",visitData[0]);
	subTime.setAttribute("hidden","hidden");
	var subRef=document.createElement("input");
	subRef.setAttribute("name","subRef");
	subRef.setAttribute("value",visitData[3]);
	subRef.setAttribute("hidden","hidden");
	var subAgent=document.createElement("input");
	subAgent.setAttribute("name","subAgent");
	subAgent.setAttribute("value",visitData[4]);
	subAgent.setAttribute("hidden","hidden");
	var subIP=document.createElement("input");
	subIP.setAttribute("name","subIP");
	subIP.setAttribute("value",visitData[1]);
	subIP.setAttribute("hidden","hidden");
	var subSession=document.createElement("input");
	subSession.setAttribute("name","subSession");
	subSession.setAttribute("value",visitData[2]);
	subSession.setAttribute("hidden","hidden");
	cmDiv.appendChild(subTime);cmDiv.appendChild(subIP);cmDiv.appendChild(subSession);cmDiv.appendChild(subRef);cmDiv.appendChild(subAgent);
	var subName=document.createElement("input");
	subName.setAttribute("id","subName");
	subName.setAttribute("autofocus","autofocus");
	subName.setAttribute("required","required");
	subName.setAttribute("type","text");
	subName.setAttribute("name","subName");
	subName.setAttribute("class","textBox");
	subName.setAttribute("placeholder","John Smith");
	cmDiv.appendChild(snLabel);cmDiv.appendChild(subName);cmDiv.appendChild(document.createElement("br"));
	var scLabel=document.createElement("label");
	scLabel.setAttribute("for","subContact");
	scLabel.setAttribute("class","textLabel");
	var subContact=document.createElement("input");
	subContact.setAttribute("id","subContact");
	subContact.setAttribute("name","subContact");
	subContact.setAttribute("required","required");
	subContact.setAttribute("class","textBox");
	subContact.setAttribute("class","textName");
	if(a=="email"){
		subContact.setAttribute("type","email");
		subContact.setAttribute("placeholder","jsmith2005@youremail.com");
		scLabel.innerHTML="Your Email Address:";
	}
	else if(a=="text"||"call"){
		subContact.setAttribute("type","tel");
		subContact.setAttribute("placeholder","(555)867-5309");
		scLabel.innerHTML="Your Phone Number:";
	}
	var subCType=document.createElement("input");
	subCType.setAttribute("name","subCType");
	subCType.setAttribute("id","subCType");
	subCType.setAttribute("value",a);
	subCType.setAttribute("hidden","hidden");
	cmDiv.appendChild(subCType);cmDiv.appendChild(scLabel);cmDiv.appendChild(subContact);cmDiv.appendChild(document.createElement("br"));
	var smLabel=document.createElement("label");
	smLabel.setAttribute("for","subMessage");
	smLabel.setAttribute("class","textLabel");
	smLabel.innerHTML="Your Message:";
	var subMessage=document.createElement("textarea");
	subMessage.setAttribute("id","subMessage");
	subMessage.setAttribute("cols","50");
	subMessage.setAttribute("rows","10");
	subMessage.setAttribute("form","contactForm");
	subMessage.setAttribute("name","subMessage");
	subMessage.setAttribute("required","required");
	subMessage.setAttribute("class","textBox");
	subMessage.setAttribute("placeholder","Write something quirky or useful here!");
	if(a=="email"){subMessage.setAttribute("maxlength","2000");}
	else if(a=="text"){subMessage.setAttribute("maxlength","450");}
	else if(a=="call"){subMessage.setAttribute("maxlength","120");}
	cmDiv.appendChild(smLabel);cmDiv.appendChild(document.createElement("br"));cmDiv.appendChild(subMessage);cmDiv.appendChild(document.createElement("br"));
	if(b=="browser"){form.appendChild(cmDiv);form.appendChild(createActionButtons());return true;}else{return cmDiv;}
}
function createActionButtons(){
	if(document.getElementById("actionButtons")){removeExisting(form,document.getElementById("actionButtons"));}
	var abDiv=document.createElement("div");
	abDiv.setAttribute("id","actionButtons");
	var subBut=document.createElement("button");
	subBut.setAttribute("type","submit");
	subBut.setAttribute("value","submit");
	subBut.setAttribute("id","sub");
	subBut.setAttribute("class","formButton");
	subBut.innerHTML="Submit Form";
	abDiv.appendChild(subBut);
	var cleBut=document.createElement("button");
	cleBut.setAttribute("id","clear");
	cleBut.setAttribute("type","button");
	cleBut.setAttribute("class","formButton");
	cleBut.setAttribute("onclick","javascript:clearForm()");
	cleBut.innerHTML="Clear Form";
	abDiv.appendChild(cleBut);
	var canBut=document.createElement("button");
	canBut.setAttribute("id","can");
	canBut.setAttribute("type","button");
	canBut.setAttribute("class","formButton");
	canBut.setAttribute("onclick","javascript:cancelForm()");
	canBut.innerHTML="Close Form";
	abDiv.appendChild(canBut);
	return abDiv;
}
function removeExisting(a,b){a.removeChild(b);}
function buildSubmit(){
	var fields=$(":input").serializeArray();
	jQuery.each(fields,function(i,fields){
		if(i==0){formVals=fields.name+"="+fields.value;}
		else{formVals+="&"+fields.name+"="+fields.value;}
	});
	return formVals;
}
function resetBox(a,b){removeExisting(formDiv,document.getElementById("contactForm"));var p=document.createElement("p");p.setAttribute("class",a);p.setAttribute("id","contactForm");p.innerHTML=b;formDiv.appendChild(p);}
function submit(){
	if(validateForm())
	{
		$.post(url,buildSubmit(),function(data, textStatus, jqXHR)
		{
			if(jqXHR.readyState!=4||jqXHR.status!=200)
			{
				resetBox("hold","Request is currently processing.  Status will be updated upon completion of request.");
			}
			else
			{
				var insert,send;
				if(data.getElementsByTagName("send")[0].firstChild.nodeValue){send=true;}else{send=false;}
				if(data.getElementsByTagName("insert")[0].firstChild.nodeValue){insert=true;}else{insert=false;}
				if(send&&insert){resetBox("good","Request was successfully sent and logged.  Thank you for your input.");}
				else if(send&&!insert){resetBox("good","Request was successfully sent.  Thank you for your input.");}
				else if(!send&&insert){resetBox("good","Request was successfully logged.  Thank you for your input.");}
				else{resetBox("bad","Request was not successful, administrator has been notified of the errors.  Thank you for your attempt, issue will be resolved within 48 hours.  Please try again later.");}
			}
		}).fail(function(jqXHR,textStatus,errorThrown){resetBox("bad","Request was not successful, administrator has been notified of the errors.  Thank you for your attempt, issue will be resolved within 48 hours.  Please try again later.");});
	}
}
function clearForm(){document.getElementById("subName").value="";document.getElementById("subContact").value="";document.getElementById("subMessage").value="";}
function cancelForm(){removeExisting(formDiv,document.getElementById("contactForm"));}
function validateForm(){
	var failReason="";var warnReason="";
	failReason+=validateName(document.getElementById("subName"));
	warnReason+=validateFreeWrites(document.getElementById("subMessage"));
	if(document.getElementById("subCType").value=="email"){failReason+=validateEmail(document.getElementById("subContact"));}
	else if(document.getElementById("subCType").value=="text"||document.getElementById("subCType").value=="call"){failReason+=validatePhone(document.getElementById("subContact"));}
      
	if(failReason!=""){alert("Some fields need correction:\n"+failReason);return false;}
	else if(warnReason!=""&&failReason==""){alert("Some fields were altered to remove illegal characters or shorten.  Submission Successful!\n");return true;}
	else{return true;}
}
function validateName(fld) {
    var error="";
    var illegalChars=/[\<\>\;\:\\\[\]\&\`\(\)\/\!\@\#\$\%\^\*\+\=\"\'\?\.\|\~]/; // allow letters and numbers and spaces
	
    if (fld.value==""){fld.style.background='Yellow';error="You didn't enter your name.\n";}
	else if ((fld.value.length<1||fld.value.length>60)){fld.style.background='Yellow';error="Your name must be less than 30 characters\n";}
	else if(illegalChars.test(fld.value)){fld.style.background='Yellow';error="Your name contains illegal characters!\n";}
	else{fld.style.background='White';}
    return error;
}
function trim1(s){return s.replace(/[\<\>\;\:\\\[\]\&\`\(\)\/\!\@\#\$\%\^\*\+\=\"\'\|\~]/g,"");}
function validateFreeWrites(fld) {
    var error="";
    var illegalChars=/[\<\>\;\:\\\[\]\&\`\(\)\/\!\@\#\$\%\^\*\+\=\"\'\|\~]/;
	fld.value=trim1(fld.value);
    if((fld.value.length<0)||(fld.value.length>450)){fld.style.background='Yellow';error="Section is to long, please shorten to 450 characters.\n";}
	else if(illegalChars.test(fld.value)){fld.style.background='Yellow';error="Comments field contains illegal characters.\n";}
	else{fld.style.background='White';}
    return error;
}
function trim(s){return s.replace(/^\s+|\s+$/,'');}
function validateEmail(fld){
    var error="";
    var tfld=trim(fld.value);                        // value of field with whitespace trimmed off
    var emailFilter=/^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
    var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
   
    if (fld.value==""){fld.style.background='Yellow';error="You didn't enter an email address.\n";}
	else if (!emailFilter.test(tfld)){fld.style.background='Yellow';error="Please enter a valid email address.\n";}
	else if (fld.value.match(illegalChars)){fld.style.background='Yellow';error="The email address contains illegal characters.\n";}
	else{fld.style.background='White';}
    return error;
}
function validatePhone(fld){
    var error="";
    var stripped=fld.value.replace(/[\(\)\.\-\ ]/g, '');    
	if(fld.value==""){error="You didn't enter a contact number.\n";fld.style.background='Yellow';}
	else if(isNaN(parseInt(stripped))){error="Your phone number contains illegal characters.\n";fld.style.background='Yellow';}
	else if (stripped.length!=10){error="Please enter your 10 digit contact number!\n";fld.style.background='Yellow';}
	else{fld.style.background='White';}
	fld.value=stripped;
    return error;
}