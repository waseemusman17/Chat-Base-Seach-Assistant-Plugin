jQuery(document).ready(function ($) {
  $("#csa-chat-toggle").on("click", function () {
    $("#csa-chat-box").show();
    $(this).hide();
  });

  $("#csa-close-btn").on("click", function () {
    $("#csa-chat-box").hide();
    $("#csa-chat-toggle").show();
  });

  $("#csa-send-btn").on("click", function () {
    const prompt = $("#csa-user-input").val().trim();
    if (!prompt) return;

    $(".chat-log").append(`<div class="user-msg">${prompt}</div>`);
    $("#csa-user-input").val("");

    $.ajax({
      method: "POST",
      url: CSA.ajax_url,
      beforeSend: function (xhr) {
        xhr.setRequestHeader("X-WP-Nonce", CSA.nonce);
      },
      data: { prompt },
      success: function (res) {
        if (res && res.response) {
          $(".chat-log").append(`<div class=\"bot-msg\">${res.response}</div>`);
        } else if (res && res.error) {
          $(".chat-log").append(`<div class=\"bot-msg\">Error: ${res.error}</div>`);
        } else {
          $(".chat-log").append(`<div class=\"bot-msg\">No response received.</div>`);
        }
      },
      error: function (xhr) {
        console.error(xhr);
        $(".chat-log").append(
          `<div class="bot-msg">Something went wrong. Please try again.</div>`
        );
      },
    });
  });
});
