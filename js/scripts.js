//var raiz="http://localhost/intranet/";
var raiz="";

$(function(){
	$(".actividad").click(function(e){
		var actividad = $(this).attr("actividad");
		e.preventDefault();
		
		window.location.href=raiz+"ver_actividad.php?id="+actividad;
	});
	$(".reto").click(function(e){
		var reto = $(this).attr("reto");
		e.preventDefault();
		
		window.location.href=raiz+"ver_reto.php?id="+reto;
	});
	$("form#login button#next_login").click(function(e){
		e.preventDefault();

		$.ajax({
			type: "POST",
			url: raiz+"api/Users.php?f=is_generated",
			data: "nick="+$("input#nick_login").val(),
			success: function(data){
				if(data=="Error"){
					$(".form-group.nick .invalid-feedback").show();
					$(".form-group.nick #nick_login").addClass("is-invalid");
				}
				else{
					$(".form-group.nick .invalid-feedback").hide();
					$("#login #next_login").hide();
					$(".form-group.nick #nick_login").attr('readonly', true).addClass("is-valid");
					if(data==""){
						$(".modal").modal();
						$("#continue-login").click(function(e){
							e.preventDefault();
							$("#continue-login").attr("disabled", true);
							$.ajax({
								type: "POST",
								url: raiz+"api/Users.php?f=send_email",
								data: "nick="+$("input#nick_login").val(),
								success: function(data){
									window.location.href=raiz+"login.php?r="+data;
								},
								error: function(error){
									window.location.href=raiz+"login.php?r=-1";
								}
							});
						});
					}
					else{
						$("#login #action_login").show();
						$("#login .pass").show();
					}
				}
			},
			error: function(error){
				console.log(error);
			}
		});
	});

	$("#pass-rep_ppal").keyup(function(key){
		$("form#setpass button").attr("disabled", true);

		if($(this).val() == $("#pass_ppal").val()){
			$("form#setpass button").attr("disabled", false);
		}
	});

	$(".source").click(function(e) {
		e.preventDefault();
		window.location.href=raiz+"source.php?user_id="+$(this).attr('user');
	})
	$(".tsource").click(function(e) {
		e.preventDefault();
		window.location.href=raiz+"source.php?team="+$(this).attr('team');
	})


	$("#register_game").click(function(e){
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: raiz+"api/Users.php?f=register_game",
			data: "id_user="+$(this).attr('user')+"&id_game="+$(this).attr('game'),
			success: function(data){
				if(data!=0){
					$(".alert").addClass("alert-danger");
					if(data==-1){
						$(".alert").text("TORTUGAAAA!! Ya se han acabado las plazas.");
					}
					else if(data==-2){
						$(".alert").text("TORTUGAAAA!! Ya se agotó el tiempo para apuntarse a este torneo.");
					}
					else{
						$(".alert").text("Vaya... nuestro programador la ha vuelto a liar. Búscalo y échale la bronca.");
					}

					$(".alert").fadeIn();
					$(".alert").delay(5000).fadeOut();
				}
				else{
					window.location.href=raiz;
				}
			},
			error: function(error){
				$(".alert").addClass("alert-danger").text("Vaya... nuestro programador la ha vuelto a liar. Búscalo y échale la bronca.");
				$(".alert").fadeIn();
				setTimeout($(".alert").fadeOut(), 5000);
			}
		});
	});

	$("#register_reto").click(function(e){
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: raiz+"api/Users.php?f=register_reto",
			data: "id_user="+$(this).attr('user')+"&id_game="+$(this).attr('game'),
			success: function(data){
				if(data!=0){
					$(".alert").addClass("alert-danger");
					if(data==-1){
						$(".alert").text("TORTUGAAAA!! Ya se han acabado las plazas.");
					}
					else if(data==-2){
						$(".alert").text("TORTUGAAAA!! Ya se agotó el tiempo para apuntarse a este torneo.");
					}
					else{
						$(".alert").text("Vaya... nuestro programador la ha vuelto a liar. Búscalo y échale la bronca.");
					}

					$(".alert").fadeIn();
					$(".alert").delay(5000).fadeOut();
				}
				else{
					window.location.href=raiz;
				}
			},
			error: function(error){
				$(".alert").addClass("alert-danger").text("Vaya... nuestro programador la ha vuelto a liar. Búscalo y échale la bronca.");
				$(".alert").fadeIn();
				setTimeout($(".alert").fadeOut(), 5000);
			}
		});
	});

	$("#register_game_team").click(function(e){
		e.preventDefault();
		
		$(".modal").modal();
	});

	$("#register_game_by_team").click(function(e){
		e.preventDefault();
		
		$.ajax({
			type: "POST",
			url: raiz+"api/Users.php?f=register_game_team",
			data: $("#team_games_parts").serialize(),
			success: function(data){
				console.log(data);
				if(data!=0){
					$(".alert").addClass("alert-danger");
					if(data==-1){
						$(".alert").text("TORTUGAAAA!! Ya se han acabado las plazas.");
					}
					else if(data==-2){
						$(".alert").text("TORTUGAAAA!! Ya se agotó el tiempo para apuntarse a este torneo.");
					}
					else{
						$(".alert").text("Vaya... nuestro programador la ha vuelto a liar. Búscalo y échale la bronca.");
					}
					
					$(".alert").fadeIn();
					$(".alert").delay(5000).fadeOut();
				}
				else{
					window.location.href=raiz;
				}
			},
			error: function(error){
				$(".alert").addClass("alert-danger").text("Vaya... nuestro programador la ha vuelto a liar. Búscalo y échale la bronca.");
				$(".alert").fadeIn();
				setTimeout($(".alert").fadeOut(), 5000);
			}
		});
	});
	
	$("#admin_op").click(function(e){
		e.preventDefault();

		$.ajax({
			type: "POST",
			url: raiz+"admin.php?f=ver_juego",
		});
	});

	$("#change_view").click(function(e){
		e.preventDefault();

		if($("#table-users").is(":visible")){
			$("#table-users").hide();
			$("#table-teams").show();
		}
		else{
			$("#table-teams").hide();
			$("#table-users").show();
		}
	});
});