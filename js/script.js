var teamMembers = $('.game-players');


$('.savoir-plus').click(function(){
	$(this).closest(".match").find('.game-players').toggleClass('visible');
	// if(teamMembers.hasClass('hidden')){
	// 	console.log($(this).parent().parent().parent().find('.game-players'));
	// 	console.log($(this).parent().parent().parent());
	// 	console.log($(this).closest(".match"));
	// 	console.log($(this));
	// 	console.log($(this).parent().parent().find('.game-players'));
	// 	$(this).closest(".match").removeClass('hidden').addClass('visible');
	// }else{
	// 	$(this).parent().parent().parent().find('.game-players').removeClass('visible').addClass('hidden');
	// }
});

