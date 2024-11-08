jQuery(document).ready(function ($) {
	$(document).on("click", ".job-alerts .running", function () {
		var alert_id = $(this).attr("alert-id");

		$.ajax({
			type: "POST",
			context: this,
			url: job_bm_job_alerts_ajax.job_bm_job_alerts_ajaxurl,
			data: { action: "job_bm_ajax_run_push_alert", alert_id: alert_id },
			success: function (data) {
				//alert(data);
				$(this).children("span").html(data);
				//$(this).parent().parent().remove();
				//$('.see-phone-number .phone-number').html(data);
				//location.reload(true);
			},
		});
	});

	$(document).on("click", ".job-alerts .delete", function () {
		var is_confirm = $(this).attr("confirm");

		if (is_confirm == "ok") {
			var alert_id = $(this).attr("alert-id");

			$.ajax({
				type: "POST",
				context: this,
				url: job_bm_job_alerts_ajax.job_bm_job_alerts_ajaxurl,
				data: { action: "job_bm_ajax_delete_alert_by_id", alert_id: alert_id },
				success: function (data) {
					//alert(data);
					$(this).html(data);
					//$(this).parent().parent().remove();
					//$('.see-phone-number .phone-number').html(data);
					//location.reload(true);
				},
			});
		} else {
			$(this).attr("confirm", "ok");
			$(this).html("Confirm");
		}
	});

	$(document).on("click", ".job-alerts .add-alert", function () {
		if ($(this).hasClass("checked")) {
			//alert('Yes');

			$(this).removeClass("checked");
			$(".create-alert").fadeOut();
		} else {
			$(this).addClass("checked");
			$(".create-alert").fadeIn();
		}
	});

	$(document).on("click", "#search-input .submit", function () {
		var keyword = $("#search-input .keywords").val();

		if (keyword != "" || keyword != null) {
			$.ajax({
				type: "POST",
				context: this,
				url: job_bm_job_alerts_ajax.job_bm_job_alerts_ajaxurl,
				data: {
					action: "job_bm_job_alerts_ajax_submit",
					keyword: keyword,
				},
				success: function (data) {},
			});
		}
	});
});
