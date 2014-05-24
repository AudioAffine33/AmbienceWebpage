/**
 * PHP. Javascript. Print_r. Nice. Object. Dumper.
 * Original. Code: http://www.openjs.com/scripts/others/dump_function_php_print_r.php
 * Modified. By. Claude. Hohl. Namics.
 */
 
function print_r(arr, level) {
 
	var dumped_text = "";
	if (!level) level = 0;
 
	//The padding given at the beginning of the line.
	var level_padding = "";
	var bracket_level_padding = "";
 
	for (var j = 0; j < level + 1; j++) level_padding += "    ";
	for (var b = 0; b < level; b++) bracket_level_padding += "    ";
 
	if (typeof(arr) == 'object') { //Array/Hashes/Objects 
		dumped_text += "Array\n";
		dumped_text += bracket_level_padding + "(\n";
		for (var item in arr) {
 
			var value = arr[item];
 
			if (typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "[" + item + "] => ";
				dumped_text += print_r(value, level + 2);
			} else {
				dumped_text += level_padding + "[" + item + "] => " + value + "\n";
			}
 
		}
		dumped_text += bracket_level_padding + ")\n\n";
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>" + arr + "<===(" + typeof(arr) + ")";
	}
 
	return dumped_text;
 
}

function displayLoginMenu(){
    if ($("#LoginMenu").css("display") == "none") {
        $("#LoginMenu").css("display", "block");
    } else {
        $("#LoginMenu").css("display", "none");
    }
}

function displayUserMenu(){
    if ($("#UserMenu").css("display") == "none") {
        $("#UserMenu").css("display", "block");
    } else {
        $("#UserMenu").css("display", "none");
    }
}