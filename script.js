for(let i = 0; i < 66; i++){
	$('<div>', {seat:i}).appendTo('#container');
}

let currentValue = document.getElementById("container").getElementsByTagName("div");
let seatingData;

const showSeats = () => {
	$(document).ready(function () {
		$.get('cinema.php?showSeats=1', function(data){
			data = JSON.parse(data);
			data.map(v => {
				$(currentValue[v]).css({"background": "#7d8c88", "cursor": "unset"}).off("click");
			})
		});
	});
}

const popup = () => {
	$("#container div").unbind( "click" );
	$("#container div").one("click", function() {
		$("#userData").css("display", "block");	
		seatingData = parseInt($(this).attr("seat"));
	});
}

const submitData = () => {
	$("#userdataForm").submit(function(e){
		e.preventDefault();
		$(document).ajaxError(function() {
			alert("Sometheing went wrong")
		});
		let postData = $(this).serializeArray();
		postData.push({'name':'seatingID', 'value': seatingData});
		$.post('cinema.php', postData, function(data){
			$("#userData").css("display", "none");
			$(currentValue[seatingData]).css("background", "#7d8c88");
			$("#thankYou").css("display", "block").html("<span>Thank you, your movie ticket is booked</span><span>X</span>");
			$("#thankYou").click(function(){$("#thankYou").css("display", "none")});
		});
	});
}

showSeats();
popup();
submitData();


