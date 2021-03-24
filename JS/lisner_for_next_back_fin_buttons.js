function next_back_display() {
	//hides divs with a name
    var class_arr = document.getElementsByClassName('quests');
    var i;
    for (i = 0; i < class_arr.length; i++) {
        class_arr[i].style.display = 'none';
    }
    //show first div
    var nr = 0;
    var show_id = document.getElementById('question'+nr);
	show_id.style.display = 'block';
	
	//get and hide buttons
	var next_btn = document.getElementById('next');
	var back_btn = document.getElementById('back');
	var fin_btn = document.getElementById('fin');
	back_btn.style.display = 'none';
	fin_btn.style.display = 'none';

	//get the next div
	next_btn.addEventListener("click",function(){
		show_id.style.display = 'none';
		nr++;
		show_id = document.getElementById('question'+nr);
		show_id.style.display = 'block';
		//show/hide button depending on div nr
		if (nr > 0) {
			back_btn.style.display = 'block';
		}else{
			back_btn.style.display = 'none';
		}
		if (nr == 9) {
			fin_btn.style.display = 'block';
			next_btn.style.display = 'none';
		}else{
			next_btn.style.display = 'block';
		}
	});
	// go back a div
	back_btn.addEventListener("click",function(){
		show_id.style.display = 'none';
		nr--;
		show_id = document.getElementById("question"+nr);
		show_id.style.display = 'block';
		//show/hide button depending on div nr
		if (nr > 0) {
			back_btn.style.display = 'block';
		}else{
			back_btn.style.display = 'none';
		}
		if (nr == 9) {
			fin_btn.style.display = 'block';
			next_btn.style.display = 'none';
		}else{
			next_btn.style.display = 'block';
		}
	});
}
//window.onload = next_back_display;