var i = 0,
	forms = document.getElementsByClassName("forms"),
	modify_dropdown = document.getElementsByClassName("modify_dropdown"),
	current_open,
	previous_button,
	iterate_once = 0;
		
for (i = 0; i < forms.length; i = i + 1) {
        forms[i].style.display = "none";
}

function openModify(dropdownID, button, type) { //type 0 is navbar. type 1 is folder rename
	var dropdown_id = document.getElementById(dropdownID); //Take the input ID name
	
	for (i = 0; i < modify_dropdown.length && type == 0; i = i + 1) { // Open the dropdown menu
        modify_dropdown[i].style.display = "block"; 
        modify_dropdown[i].style.animation = "slide_open .4s cubic-bezier(0,0,0,1) forwards";
    }
    
    if (current_open != null) { // make the previous dropdown menu options dissapear, if there was one
		//current_open.style.display = "none";
	}
	
	if (current_open != null && current_open != dropdown_id) { // if same button is not pressed twice, reset counter
		current_open.style.display = "none";
		//previous_button.style.background = "#566779";
		//previous_button.style.color = "white";
		iterate_once = 0;
	}
	
	if (current_open == dropdown_id && iterate_once == 0) {
	    dropdown_id.style.animation = "unfadeReverse .65s ease-out 0s forwards";
	    setTimeout(function(){ current_open.style.display = "none"; }, 880);
	    
	    for (i = 0; i < modify_dropdown.length && type == 0; i = i + 1) { // Open the dropdown menu
			modify_dropdown[i].style.animation = "slide_openReverse .4s cubic-bezier(0,0,0,1) .6s forwards";
			//modify_dropdown[i].style.display = "none";
		}
		//current_open = 0; //reset current open so that the user can continusly click the same button and have rotating animations
		//previous_button.style.background = "#525252";
		//previous_button.style.color = "white";
		iterate_once += 1;
	} else if (current_open == dropdown_id && iterate_once == 1) { //This will reset the counter so that the same button can be mashed repeatidly
		iterate_once = 0;
		dropdown_id.style.display = "block"; // Display dropdown menu options
		dropdown_id.style.animation = "unfade .65s ease-out .35s forwards";
		//button.style.background = "#ececec";
		button.style.color = "#566779";
	} else if (current_open != dropdown_id) {
		dropdown_id.style.display = "block"; // Display dropdown menu options
		dropdown_id.style.opacity = "0";
		dropdown_id.style.animation = "unfade .65s ease-out .35s forwards";
		//button.style.background = "#ececec";
		button.style.color = "#566779";
	}
	    
   current_open = dropdown_id; 
   previous_button = button;
}

function checkboxclick(itemID, rename, checkbox) {
	var item = document.getElementById(itemID),
		renameLink = document.getElementById(rename),
		checkboxID = document.getElementById(checkbox);
	
	if(checkboxID.checked == false) { //if checkbox is checked, turn the background to white
		item.style.background = "white";
		renameLink.style.background = "white";
	} else {
		item.style.background = "#abdfff";
		renameLink.style.background = "#abdfff";
	}
}

function scale() {
		var navbar = document.getElementById("navbar"),
			filemanager = document.getElementById("filemanager"),
			html = document.getElementById("html"),
			fileDIV = document.getElementsByClassName("fileDiv"),
			folderDIV = document.getElementsByClassName("folderDiv"),
			checkboxDiv = document.getElementsByClassName("modify");
		
		if (html.offsetWidth > 750) { //if width is beyond 750px, just go off of css rules
			filemanager.style.width = html.offsetWidth - navbar.offsetWidth + "px";
			for (i = 0; i < modify_dropdown.length; i = i + 1) { // Open the dropdown menu
				modify_dropdown[i].style.left = "30px";
			}
		} else { // if not <750px, do some adjustments to the dropwdown menu
			filemanager.style.width = "100%";
			
			for (i = 0; i < modify_dropdown.length; i = i + 1) { // Open the dropdown menu
				modify_dropdown[i].style.left = (html.offsetWidth - modify_dropdown[i].offsetWidth)/2 + "px";
			}
		}
		
/*
for (i = 0; i < fileDIV.length; i = i + 1) { // Open the dropdown menu
				if (checkboxDiv[i] !== null && fileDIV[i] !== null) {
					if (checkboxDiv[i].checked == false) {
						fileDIV[i].style.background = "white";
						//renameLink.style.background = "white";
						alert(i + "is not checked");
					} else if (checkboxDiv[i].checked == true) {
						fileDIV[i].style.background = "#abdfff";
						//renameLink.style.background = "#abdfff";
					}
				}
		}


*/
		
		
}
