<script>

var totalList = 7;
var list = $('#sidebar li');
var prev = $('.pager .previous');
var next = $('.pager .next');

//add active class to sidebar
list[activeSidebar].classList.add('active');

//add pagination
if (activeSidebar == 0) {
	prev.addClass('disabled');
}
else {
	prev.find('a')[0].href = list[activeSidebar-1].querySelector('a').href;
}

if (activeSidebar == 7) {
	next.addClass('disabled');
}
else {
	next.find('a')[0].href = list[activeSidebar+1].querySelector('a').href;
}

</script>