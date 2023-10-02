var w = window;

// Custom functions that can be binded to a jQuery element

// Clear the file from an input
$.fn.clearFile = function () {
  var $form = $(this);

  $form.replaceWith($form.val("").clone(true));
};

// Shows an empty list markup if a list of something is empty
$.fn.emptyList = function () {
  var $list = $(this);

  $list.html("");

  $("<div>", {
    class: "empty",
  }).html('<i class="fa fa-list"></i>')
    .appendTo($list);
};

// Shows a failed to load alert box if ajax request fails
$.fn.failedToLoad = function (string) {
  var $parent = $(this);

  $parent.html("");

  $("<div>", {
    class: "card card-sm card-centered alert alert-danger alert-icon",
  }).html('<i class="fa fa-exclamation-triangle"></i><h1>Something went wrong</h1><p>' + string + "</p>")
    .appendTo($parent);
};

// Shows an input error
$.fn.showError = function (msg, show) {
  var $parent = $(this);

  if (!show) return $parent.html("");

  $("<div>", {
    class: "form-alert",
  }).text(msg)
    .appendTo($parent);
};

// Show or hide error in input boxes
$.fn.toggleInputError = function (toggle, msg) {
  var $input = $(this),
    $label = $input.next(".input-sub-label"),
    isShown = $input.hasClass("input-error");

  if (toggle) {
    if (!isShown) {
      $input.addClass("input-error");
      $label.html(msg);
    }
    return;
  }

  if (isShown) {
    $input.removeClass("input-error");
    $label.html("");
  }
};

// Checks whether a string is in email format or not
function isEmail(string) {
  return /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(string);
}

// Checks whether the element with the id attribute exists
function elementExists(elem) {
  return $(elem).length > 0;
}

// Creates a table element and assigns the table style
function createTable(object) {
  var tableContainer = $("<div>", {
      class: "table-wrapper",
    }).appendTo(object.parent),

    table = $("<table>", {
      class: "table table-stripped mt-1",
    }).appendTo(tableContainer),
    
    row = $("<tr>").appendTo(table);

  for (var i = 0, j = object.headers.length; i < j; ++i) {
    $("<th>").text(object.headers[i]).appendTo(row);
  }

  return table;
}

// Shows an article preview (used for news and trading analysis)
$.fn.previewArticle = function (data) {
  var $previewer = $(this);

  if ($previewer.length) {
    var $titleHandler = $previewer.find("#preview-article-title"),
      $bodyHandler = $previewer.find("#preview-article-body");

    $titleHandler.text(data.title);
    $bodyHandler.html(data.body);
    $previewer.addClass("active");

    $("#preview-article-close").on("click", function () {
      if ($previewer.hasClass("active")) {
        $previewer.removeClass("active");
      }
    });
  }
};

// Shows a dialog of specific types default/error/success/warning
function showDialog(text, type) {
  var icon = {
    error: "fa-exclamation-triangle txt-danger txt-lg mb-1",
    success: "fa-check txt-success txt-lg mb-1",
    warning: "fa-exclamation-circle txt-warning txt-lg mb-1",
  };

  return Dialog({
    title: `<i class="fa ${icon[type]}"></i>`,
    html: text,
  }, type);
}

// Shows a sweet alert modal dialog
function Dialog(config) {
  var dialog = Swal.mixin({
    background: "#111",
    backdrop: "rgba(0,0,0,0.7)",
    customClass: {
      confirmButton: "modal-btn modal-btn-primary",
      cancelButton: "modal-btn modal-btn-secondary",
    },
    buttonsStyling: false,
  });

  return dialog.fire(config);
}

// Shows a loading modal dialog
function processing(title) {
  Dialog({
    html: '<div class="spinner"></div>' + title,
    background: "transparent",
    showConfirmButton: false,
    allowOutsideClick: false,
  });
}

// Shows an image cropper modal dialog using sweetalert
function cropImage(data) {
  if (w.cropper) return false;

  var markup = `
    <div id="cropped-thumb">
      <i class="fa fa-spinner fa-spin" id="thumb-loader"></i>
    </div>`;

  w.croppedImage = undefined;
  Dialog({
    title: "Crop image",
    html: markup,
    width: "850px",
    showCancelButton: true,
    confirmButtonText: '<i class="fa fa-scissors"></i> Crop',
    cancelButtonText: '<i class="fa fa-close"></i> Cancel',
    onOpen: function () {
      Swal.getConfirmButton().setAttribute("disabled", true);
      Swal.getCancelButton().setAttribute("disabled", true);
    },
  }).then(function (res) {
    if (!res.value) {
      w.cropper = undefined;
      return data.imageInput.clearFile();
    }

    w.cropper
      .croppie("result", {
        type: "blob",
        format: "png",
      })
      .then(function (blob) {
        data.previewer.html("");

        var previewContainer = $("<div>", {
            class: "flex",
          }).appendTo(data.previewer),
          url = w.URL || w.webkitURL,
          containerMarkup = `
            <h3><i class="fa fa-check"></i> Cropped</h3> ${data.image.name}
            <p class="mt-1 txt-grey">
              To change the image, click Select Another button and the current image will be replaced with the new one.
            </p>`;

        $("<img>", {
          src: url.createObjectURL(blob),
        }).appendTo(previewContainer);

        $("<div>", {
          class: "m-1 txt-success",
        })
          .html(containerMarkup)
          .appendTo(previewContainer);

        data.imageInput
          .next("label")
          .html('<i class="fa fa-image"></i> select another');

        w.croppedImage = blob;
        w.cropper = undefined;
      });
  });
  if (Swal.isVisible()) {
    setTimeout(function () {
      var fileReader = new FileReader();

      Swal.getConfirmButton().removeAttribute("disabled");
      Swal.getCancelButton().removeAttribute("disabled");

      fileReader.onload = function (e) {
        var cropper = $("#cropped-thumb");

        w.cropper = cropper.croppie({
          enableExif: true,
          viewport: {
            height: 500,
            width: 700,
          },
          boundary: {
            height: 520,
            width: 720,
          },
        });

        cropper.croppie("bind", {
          url: e.target.result,
        });

        $("#thumb-loader").css("opacity", "0");
      };
      fileReader.readAsDataURL(data.image);
    }, 1000);
  }
}

$(document).ready(function () {
  var $doc = $(this);

  if (w._USER_DATA) {
    var currentUser = w._USER_DATA,
      baseURL = `/user/${currentUser.username}`;
  }

  /** ---------------------------------------------------------------------
   * Logout script
   * ---------------------------------------------------------------------
   */

  if (elementExists("#logout-btn")) {
    $("#logout-btn").on("click", function () {
      var continueTo = $(this).data("continue");

      Dialog({
        text: "Are you sure you want to logout?",
        confirmButtonText: "Logout",
        showCancelButton: true,
      }).then(function (result) {
        if (result.value) {
          w.location = continueTo;
        }
      });
    });
  }

  /** ---------------------------------------------------------------------
   * News script
   * ---------------------------------------------------------------------
   */

  // News list
  if (elementExists("#news-list")) {
    var $newsList = $("#news-list");

    // Load the news list
    function loadNewsList() {
      $.get("/user/scripts/news/read.php")
        .done(function (response) {
          renderNewsList(JSON.parse(response));
        })
        .fail(function () {
          $newsList.failedToLoad("Could not load due to an anonymous error.");
        });
    }

    // Render the news list table
    function renderNewsList(data) {
      if (!data.length) return $newsList.emptyList();

      $newsList
        .addClass("txt-grey txt-mds")
        .html("All the news posted at GoldenRules");

      var table = createTable({
        parent: $newsList,
        headers: ["ID", "Title", "Author", "Posted", "Action"],
      });

      for (var i = 0, j = data.length; i < j; i++) {
        var news = data[i],
          rows = $("<tr>", {
            id: news.id,
          }).appendTo(table);

        $("<td>").text(news.id).appendTo(rows);

        $("<a>", {
          class: "link-gold link-no-underline",
          href: news.url,
          target: "_blank",
          title: "Click to view",
        })
          .text(news.title)
          .appendTo($("<td>").appendTo(rows));

        $("<td>").text(news.author).appendTo(rows);

        $("<td>").text(news.date).appendTo(rows);

        var actionColumn = $("<td>").appendTo(rows);

        // Delete button
        $("<i>", {
          class: "fa fa-trash icon-btn icon-btn-danger",
          title: "Delete this news",
          id: "delete-news-btn",
          "data-delete-news-id": news.id,
          "data-delete-news-title": news.title,
          "data-delete-news-thumb": news.thumbnail_url,
        }).appendTo(actionColumn);

        // Edit button
        $("<i>", {
          class: "fa fa-pencil icon-btn icon-btn-warning",
          title: "Edit this news",
          id: "edit-news-btn",
          "data-edit-news-url": `${baseURL}/news?action=edit&news=${news.id}`,
        }).appendTo(actionColumn);
      }
    }

    // Deleting the news (triggered when user clicks delete button in the table)
    $doc.on("click", "#delete-news-btn", function () {
      var $btn = $(this),
        newsID = $btn.data("delete-news-id");

      Dialog({
        title: "Delete news?",
        text: "This action permanently deletes this news from the database and cannot be undone.",
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-trash"></i> Delete',
        cancelButtonText: '<i class="fa fa-close"></i> Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function () {
          return $.post({
            url: "/user/scripts/news/delete.php",
            data: {
              grt_news_delete_data: JSON.stringify({
                news_id: newsID,
                news_title: $btn.data("delete-news-title"),
                news_thumbnail_url: $btn.data("delete-news-thumb"),
                csrf_token: $newsList.data("token"),
              }),
            },
          })
            .done(function (response) {
              var res = JSON.parse(response);

              if (res["status"] == "error")
                showDialog(res["message"], res["status"]);
            })
            .fail(function () {
              showDialog(
                "Could not proceed due to an anonymous error.",
                "error"
              );
            });
        },
      }).then(function (res) {
        if (res.value) {
          var rsp = JSON.parse(res.value);

          showDialog(rsp.message, "success");
          $("#" + newsID).remove();
        }
      });
    });

    // Redirect to edit news page upon clicking the edit button
    $doc.on("click", "#edit-news-btn", function () {
      w.location = $(this).data("edit-news-url");
    });

    // Load news list
    loadNewsList();
  }

  // Creating a news post
  if (elementExists("#news-form")) {
    var $newsForm = $("#news-form"),
      $newsContents = $("#news-body-contents"),
      $newsTitle = $("#news-title"),
      $newsPostButton = $("#post-news-btn"),
      $newsPreviewButton = $("#preview-news-btn"),
      $newsThumbnail = $("#news-upload-thumb");

    $newsContents.trumbowyg();

    $newsForm.on("submit", function (e) {
      e.preventDefault();

      if ($newsContents.val().length < 500)
        return showDialog(
          "News contents must contain at least 500 characters or more.",
          "error"
        );

      processing("Posting...");

      var $form = $(this),
        formData = new FormData();

      formData.append(
        "grt_news_data",
        JSON.stringify({
          news_title: $newsTitle.val(),
          news_body: $newsContents.trumbowyg("html"),
          csrf_token: $form.data("token"),
        })
      );

      formData.append("news_thumbnail", w.croppedImage);

      $.post({
        url: "/user/scripts/news/create.php",
        data: formData,
        processData: false,
        contentType: false,
      })
        .done(function (response) {
          var res = JSON.parse(response);

          if (res["status"] == "error")
            return showDialog(res["message"], "error");

          if (res["status"] == "success") {
            showDialog(res["message"], "success").then(function () {
              $form.find(":input").val("");

              $newsThumbnail
                .next("label")
                .html('<i class="fa fa-image"></i> select image')
                .clearFile();

              $newsContents.trumbowyg("empty");
              $newsPostButton.attr("disabled", true);
              $newsPreviewButton.attr("disabled", true);

              $("#cropped-thumbnail-preview").html("");

              w.croppedImage = undefined;
              w.cropper = undefined;
            });
          }
        })
        .fail(function () {
          showDialog("Could not proceed due to an anonymous error.", "error");
        });
    });

    // Validating the title input
    $newsTitle.on("input", function () {
      var $input = $(this),
        value = $input.val(),
        msg = "",
        status = false,
        toggleButtons = false,
        len = value.length;

      if (
        (len >= 1 && len < 30) ||
        /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/.test(value)
      ) {
        msg = "Minimum of 30 characters and no symbols";
        status = true;
      }

      if (len >= 1 && len > 100) {
        msg = "Length exceeds maximum limit (100 chars)";
        status = true;
      }

      $input.toggleInputError(status, msg);

      if (!value || $input.hasClass("input-error")) toggleButtons = true;

      $newsPostButton.attr("disabled", toggleButtons);
      $newsPreviewButton.attr("disabled", toggleButtons);
    });

    // Cropping thumbnail before preparing to for upload
    $newsThumbnail.on("change", function () {
      var $input = $(this),
        file = $input.prop("files")[0];

      if (file.size / 1000 > $input.data("max-size")) {
        showDialog(
          "This image size is too large, please select an image less than 1MB.",
          "error"
        );
        return $input.clearFile();
      }

      cropImage({
        image: file,
        previewer: $("#cropped-thumbnail-preview"),
        imageInput: $input,
      });
    });

    // Previewing the news
    $newsPreviewButton.on("click", function () {
      $("#preview-article").previewArticle({
        title: $newsTitle.val(),
        body: $newsContents.trumbowyg("html"),
      });
    });
  }

  // Editing news
  if (elementExists("#edit-news-form")) {
    var $editNewsForm = $("#edit-news-form"),
      $editNewsContents = $("#edit-news-body-contents"),
      $editNewsTitle = $("#edit-news-title"),
      $editNewsSaveButton = $("#save-news-btn"),
      $editNewsPreviewButton = $("#preview-edited-news-btn"),
      $editNewsThumbnail = $("#edit-news-thumb"),
      newsData = w._EDITABLE_NEWS_DATA;

    $editNewsContents.trumbowyg().trumbowyg("html", newsData.body);

    $editNewsForm.on("submit", function (e) {
      e.preventDefault();

      var newsBody = $editNewsContents.val(),
        title = $editNewsTitle.val();

      if (newsBody.length < 500)
        return showDialog(
          "News contents must have at least 500 characters or more.",
          "error"
        );

      var bodyContents = $editNewsContents.trumbowyg("html");

      if (
        !w.__removingThumbnail &&
        !w.croppedImage &&
        title == newsData.title &&
        bodyContents == newsData.body
      )
        return showDialog(
          "Make changes to at least one of the fields before saving.",
          "error"
        );

      processing("Saving...");

      var $form = $(this),
        formData = new FormData();

      formData.append(
        "grt_news_updated_data",
        JSON.stringify({
          edited_news_id: newsData.id,
          edited_news_title: title,
          edited_news_body: bodyContents,
          edited_news_current_thumb: newsData.thumbnail_url,
          is_removing_thumbnail: w.__removingThumbnail,
          csrf_token: $form.data("token"),
        })
      );

      formData.append("edited_news_thumbnail", w.croppedImage);

      $.post({
        url: "/user/scripts/news/update.php",
        data: formData,
        processData: false,
        contentType: false,
      })
        .done(function (response) {
          var res = JSON.parse(response);

          if (res["status"] == "error")
            return showDialog(res["message"], "error");

          if (res["status"] == "success") {
            showDialog(res["message"], "success").then(function (res) {
              w.location.reload();

              if (w.__removingThumbnail) {
                w.__removingThumbnail = undefined;
              }
            });
          }
        })
        .fail(function () {
          showDialog("Could not proceed due to an anonymous error", "error");
        });
    });

    // Validating inputs
    $editNewsTitle.on("input", function () {
      var $input = $(this),
        value = $input.val(),
        msg = "",
        status = false,
        toggleButtons = false,
        len = value.length;

      if (
        (len >= 1 && len < 30) ||
        /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/.test(value)
      ) {
        msg = "Minimum of 30 characters and no symbols";
        status = true;
      }

      if (len >= 1 && len > 100) {
        msg = "Length exceeds maximum limit (100 chars";
        status = true;
      }

      $input.toggleInputError(status, msg);

      if (!value || $input.hasClass("input-error")) toggleButtons = true;

      $editNewsSaveButton.attr("disabled", toggleButtons);
      $editNewsPreviewButton.attr("disabled", toggleButtons);
    });

    // Cropping thumbnail before preparing for upload
    $editNewsThumbnail.on("change", function () {
      var $input = $(this),
        file = $input.prop("files")[0];

      // Don't accept more than 1MB
      if (file.size / 1000 > $input.data("max-size")) {
        showDialog(
          "This image size is too large, please select an image less than 1MB.",
          "error"
        );
        return $input.clearFile();
      }
      cropImage({
        image: file,
        previewer: $("#cropped-thumbnail-preview"),
        imageInput: $input,
      });
    });

    // Removing thumbnail before saving
    $("#remove-thumbnail").on("click", function () {
      var $btn = $(this);
      Dialog({
        title: "Remove Thumbnail?",
        text: "Are you sure you want to remove this thumbnail?",
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-trash"></i> Remove',
        cancelButtonText: '<i class="fa fa-close"></i> Cancel',
      }).then(function (res) {
        if (res.value) {
          var markup = `<div class="alert alert-default alert-icon">
              <i class="fa fa-check-circle"></i>
              <h3>Thumbnail removed</h3>
              <p>Changes will be saved once you click save button.</p>
            </div>`;

          $btn.hide();
          $("#cropped-thumbnail-preview").html(markup);
          w.__removingThumbnail = true;
        }
      });
    });

    // Previewing the news
    $editNewsPreviewButton.on("click", function () {
      $("#preview-article").previewArticle({
        title: $editNewsTitle.val(),
        body: $editNewsContents.trumbowyg("html"),
      });
    });
  }
  /** ---------------------------------------------------------------------
   * Trading analysis script
   * ---------------------------------------------------------------------
   */

  // Trading analysis list
  if (elementExists("#trading-analysis-list")) {
    var $analysisList = $("#trading-analysis-list"),
      param = !currentUser.is_admin ? "?user_id=" + currentUser.id : "";

    function loadAnalysisList() {
      $.get("/user/scripts/analysis/read.php" + param)
        .done(function (res) {
          renderAnalysisList(JSON.parse(res));
        })
        .fail(function () {
          $analysisList.failedToLoad(
            "Could not load due to an anonymous error."
          );
        });
    }

    function renderAnalysisList(data) {
      if (!data.length) return $analysisList.emptyList();

      var headerText = !currentUser.is_admin
        ? currentUser.first_name +
          ", Here is a list of trading analysis you have posted so far"
        : "All the trading analysis posted by the GoldenRules users";

      $analysisList
        .attr({
          class: "txt-grey",
        })
        .html(headerText);

      var tableHeaders = ["ID", "Title", "Posted", "Action"];

      /**
       * If the current user is an admin, insert one more
       * column to show the author of the analysis. Since
       * we're showing all the analysis for the admin user,
       * it's better to show the author along
       */
      if (currentUser.is_admin) tableHeaders.splice(2, 0, "Author");

      var table = createTable({
        parent: $analysisList,
        headers: tableHeaders,
      });

      for (var i = 0, j = data.length; i < j; i++) {
        var analysis = data[i],
          rows = $("<tr>", {
            id: analysis.id,
          }).appendTo(table);

        $("<td>").text(analysis.id).appendTo(rows);

        $("<a>", {
          class: "link-gold link-no-underline",
          href: `${window.origin}/trading-analysis/${analysis.url}`,
          target: "_blank",
          title: "Click to view",
        })
          .text(analysis.title)
          .appendTo($("<td>").appendTo(rows));

        /**
         * Show the author name in the column
         * if the current user is an admin
         */
        if (currentUser.is_admin) {
          $("<td>").text(analysis.author).appendTo(rows);
        }

        $("<td>").text(analysis.date).appendTo(rows);

        var actionColumn = $("<td>").appendTo(rows);

        // Delete button
        $("<i>", {
          class: "fa fa-trash icon-btn icon-btn-danger",
          title: "Delete this trading analysis",
          id: "delete-analysis-btn",
          "data-delete-analysis-id": analysis.id,
          "data-delete-analysis-title": analysis.title,
          "data-delete-analysis-thumb": analysis.thumbnail_url,
        }).appendTo(actionColumn);

        // Edit button
        $("<i>", {
          class: "fa fa-pencil icon-btn icon-btn-warning",
          title: "Edit this trading analysis",
          id: "edit-analysis-btn",
          "data-edit-analysis-url": `${baseURL}/trading-analysis?action=edit&analysis=${analysis.url}`,
        }).appendTo(actionColumn);
      }
    }

    // Deleting the trading analysis (triggered when user clicks delete button in the table)
    $doc.on("click", "#delete-analysis-btn", function (e) {
      var $btn = $(this),
        analysisID = $btn.data("delete-analysis-id");

      Dialog({
        title: "Delete trading analysis?",
        text: "This action permanently deletes this trading analysis from the database and cannot be undone.",
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-trash"></i> Delete',
        cancelButtonText: '<i class="fa fa-close"></i> Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function () {
          return $.post({
            url: "/user/scripts/analysis/delete.php",
            data: {
              grt_analysis_delete_data: JSON.stringify({
                analysis_id: analysisID,
                analysis_title: $btn.data("delete-analysis-title"),
                analysis_thumbnail_url: $btn.data("delete-analysis-thumb"),
                csrf_token: $analysisList.data("token"),
              }),
            },
          })
            .done(function (response) {
              var res = JSON.parse(response);

              if (res["status"] == "error") showDialog(res["message"], "error");
            })
            .fail(function () {
              showDialog(
                "Could not proceed due to an anonymous error.",
                "error"
              );
            });
        },
      }).then(function (res) {
        if (res.value) {
          var rsp = JSON.parse(res.value);

          showDialog(rsp.message, "success");
          $("#" + analysisID).remove();
        }
      });
    });

    // Redirect to edit page upon clicking the edit button
    $doc.on("click", "#edit-analysis-btn", function () {
      w.location = $(this).data("edit-analysis-url");
    });

    // Load trading analysis
    loadAnalysisList();
  }

  // Creating a new analysis post
  if (elementExists("#analysis-form")) {
    var $analysisForm = $("#analysis-form"),
      $analysisContents = $("#analysis-body-contents"),
      $analysisTitle = $("#analysis-title"),
      $analysisPostButton = $("#post-analysis-btn"),
      $analysisPreviewButton = $("#preview-analysis-btn"),
      $analysisThumbnail = $("#analysis-upload-thumb");

    $analysisContents.trumbowyg();
    $analysisForm.on("submit", function (e) {
      e.preventDefault();

      var title = $analysisTitle.val();

      if (/[`$%^&*()+\=\[\]{}'"\\|<>\/~]/.test(title))
        return showDialog(
          "Illegal characters found in the title field.<br>Allowed characters are _-.:#,?!;@",
          "error"
        );

      if (w.croppedImage === undefined)
        return showDialog(
          "Please select a trading analysis chart image.",
          "error"
        );

      if ($analysisContents.val().length < 50)
        return showDialog(
          "The trading analysis description must have at least 50 characters or more.",
          "error"
        );

      processing("Posting...");

      var $form = $(this),
        formData = new FormData();

      formData.append(
        "grt_analysis_data",
        JSON.stringify({
          analysis_title: title,
          analysis_body: $analysisContents.trumbowyg("html"),
          csrf_token: $form.data("token"),
        })
      );

      formData.append("analysis_thumbnail", w.croppedImage);

      $.post({
        url: "/user/scripts/analysis/create.php",
        data: formData,
        processData: false,
        contentType: false,
      })
        .done(function (response) {
          var res = JSON.parse(response);

          if (res["status"] == "error")
            return showDialog(res["message"], "error");

          if (res["status"] == "success") {
            showDialog(res["message"], "success").then(function () {
              $form.find(":input").val("");

              $analysisThumbnail
                .next("label")
                .html('<i class="fa fa-image"></i> select image')
                .clearFile();

              $analysisContents.trumbowyg("empty");
              $analysisPostButton.attr("disabled", true);

              $("#cropped-thumbnail-preview").html("");

              w.croppedImage = undefined;
              w.cropper = undefined;
            });
          }
        })
        .fail(function () {
          showDialog("Could not proceed due to an anonymous error.", "error");
        });
    });

    // Validating inputs
    $analysisTitle.on("input", function () {
      var $input = $(this),
        value = $input.val(),
        msg = "",
        status = false,
        toggleButton = false,
        len = value.length;

      if (len >= 1 && len < 30) {
        msg = "Should not be less than 30 characters";
        status = true;
      }

      if (len >= 1 && len > 100) {
        msg = "Should not be higher than 100 characters";
        status = true;
      }

      $input.toggleInputError(status, msg);

      if (!value || $input.hasClass("input-error")) toggleButton = true;

      $analysisPostButton.attr("disabled", toggleButton);
      $analysisPreviewButton.attr("disabled", toggleButton);
    });

    // Cropping thumbnail before preparing to for upload
    $analysisThumbnail.on("change", function () {
      var $input = $(this),
        file = $input.prop("files")[0];

      if (file.size / 1000 > $input.data("max-size")) {
        showDialog(
          "This image size is too large, please select an image less than 1MB.",
          "error"
        );
        return $input.clearFile();
      }

      cropImage({
        image: file,
        previewer: $("#cropped-thumbnail-preview"),
        imageInput: $input,
      });
    });

    // Previewing the trading analysis
    $analysisPreviewButton.on("click", function () {
      $("#preview-article").previewArticle({
        title: $analysisTitle.val(),
        body: $analysisContents.trumbowyg("html"),
      });
    });
  }

  // Editing trading analysis
  if (elementExists("#edit-analysis-form")) {
    var $editAnalysisContents = $("#edit-analysis-body-contents"),
      $editAnalysisTitle = $("#edit-analysis-title"),
      $editAnalysisSaveButton = $("#save-analysis-btn");
    $editAnalysisPreviewButton = $("#preview-edited-analysis-btn");
    ($editAnalysisThumbnail = $("#edit-analysis-thumb")),
      (analysisData = w._EDITABLE_ANALYSIS_DATA);

    $editAnalysisContents.trumbowyg().trumbowyg("html", analysisData.body);

    $("#edit-analysis-form").on("submit", function (e) {
      e.preventDefault();

      var title = $editAnalysisTitle.val();

      if (/[`$%^&*()+\=\[\]{}'"\\|<>\/~]/.test(title))
        return showDialog(
          "Illegal characters found in the title field<br>Allowed characters are _-.:#,?!;@",
          "error"
        );

      if ($editAnalysisContents.val().length < 50)
        return showDialog(
          "The trading analysis description must contain at least 50 characters or more.",
          "error"
        );

      var analysisBody = $editAnalysisContents.trumbowyg("html");

      if (
        !w.croppedImage &&
        title == analysisData.title &&
        analysisBody == analysisData.body
      )
        return showDialog(
          "Make changes to at least one of the fields before saving",
          "error"
        );

      processing("Saving...");

      var $form = $(this),
        formData = new FormData();

      formData.append(
        "grt_analysis_updated_data",
        JSON.stringify({
          edited_analysis_id: analysisData.id,
          edited_analysis_title: title,
          edited_analysis_body: analysisBody,
          edited_analysis_current_thumb: analysisData.thumbnail_url,
          csrf_token: $form.data("token"),
        })
      );

      if (w.croppedImage)
        formData.append("edited_analysis_thumbnail", w.croppedImage);

      $.post({
        url: "/user/scripts/analysis/update.php",
        data: formData,
        processData: false,
        contentType: false,
      })
        .done(function (response) {
          var res = JSON.parse(response);

          if (res["status"] == "error")
            return showDialog(res["message"], "error");

          if (res["status"] == "success") {
            showDialog(res["message"], "success").then(function () {
              w.location.reload();
            });
          }
        })
        .fail(function () {
          showDialog("Could not proceed due to an anonymous error.", "error");
        });
    });

    // Validating inputs
    $editAnalysisTitle.on("input", function () {
      var $input = $(this),
        value = $input.val(),
        msg = "",
        status = false,
        toggleButtons = false,
        len = value.length;

      if (len >= 1 && len < 30) {
        msg = "Should not be less than 30 characters";
        status = true;
      }

      if (len >= 1 && len > 100) {
        msg = "Should not be more than 100 characters";
        status = true;
      }

      $input.toggleInputError(status, msg);

      if (!value || $input.hasClass("input-error")) toggleButtons = true;

      $editAnalysisSaveButton.attr("disabled", toggleButtons);
      $editAnalysisPreviewButton.attr("disabled", toggleButtons);
    });

    // Cropping thumbnail before preparing to for upload
    $editAnalysisThumbnail.on("change", function () {
      var $input = $(this),
        file = $input.prop("files")[0];

      // Don't accept more than 1MB
      if (file.size / 1000 > $input.data("max-size")) {
        showDialog(
          "This image size is too large, please select an image less than 1MB.",
          "error"
        );
        return $input.clearFile();
      }
      cropImage({
        image: file,
        previewer: $("#cropped-thumbnail-preview"),
        imageInput: $input,
      });
    });

    // Previewing the trading analysis
    $editAnalysisPreviewButton.on("click", function () {
      $("#preview-article").previewArticle({
        title: $editAnalysisTitle.val(),
        body: $editAnalysisContents.trumbowyg("html"),
      });
    });
  }

  /* ----------------------------------------------------------------------------
   * Users script
   * ----------------------------------------------------------------------------
   */

  // Fetching users list
  if (elementExists("#user-list")) {
    var $userList = $("#user-list");

    $.get("/user/scripts/user/get_list.php")
      .done(function (response) {
        var data = JSON.parse(response);

        if (!data.length) return $userList.emptyList();

        $userList
          .attr({
            class: "txt-grey",
          })
          .html(
            'All registered users at GoldenRules<p class="txt-sm txt-dark-grey">You can click a user to view their account statistics</p>'
          );

        var list = $("<ol>", {
          class: "ml-2 mt-2",
        }).appendTo($userList);

        for (var i = 0, j = data.length; i < j; i++) {
          var user = data[i],
            listItem = $("<li>", {
              class: "mb-1",
            }).appendTo(list);

          $("<a>")
            .attr({
              class: "link-gold link-no-underline",
              href: `${baseURL}/users?u=${user.uid_token}`,
              title: `Click to view ${user.name}'s information`,
            })
            .text(user.name)
            .appendTo(listItem);
        }
      })
      .fail(function () {
        $userList.failedToLoad("Could not load due to an anonymous error.");
      });
  }

  /**
   * Fetching the user location with the ip address
   *
   * Since we're using the ipinfo.io's api, it takes a little
   * long for page to load so we use JS to load it with loading animation
   */

  if (elementExists("#user-location")) {
    var $userLocation = $("#user-location"),
      ip = $userLocation.data("ip");

    if (ip == "Unknown") return $userLocation.text(ip);

    $.post({
      url: "/user/scripts/user/get_location.php",
      data: {
        grt_user_ip: JSON.stringify({
          ip: ip,
        }),
      },
    })
      .done(function (response) {
        $userLocation.html("");

        var res = JSON.parse(response);

        if (res.length == 0 || res.response == "error")
          return $userLocation.text("Unknown");

        $("<span>", {
          class: "flag-icon flag-icon-" + res.alpha,
        }).appendTo($userLocation);

        $userLocation.html(
          `${$userLocation.html()} ${res.city}, ${res.country}`
        );
      })
      .fail(function () {
        $userLocation.text("Unknown");
      });
  }

  /** ---------------------------------------------------------------------
   * Password recovery script
   * ---------------------------------------------------------------------
   */

  if (elementExists("#password-recover-form")) {
    var $passwordRecoverForm = $("#password-recover-form"),
      $passwordRecoverEmail = $("#password-recover-email"),
      $passwordRecoverBtn = $("#password-recover-btn");

    $passwordRecoverForm.on("submit", function (e) {
      e.preventDefault();

      var $form = $(this),
        formData = new FormData();

      processing("Please wait...");
      formData.append(
        "grt_password_recover_email",
        $passwordRecoverEmail.val()
      );

      $.post({
        url: "/user/scripts/user_password_recover.php",
        data: formData,
        processData: false,
        contentType: false,
      })
        .done(function (response) {
          var res = JSON.parse(response);

          if (res["status"] == "error")
            return showDialog(res["message"], "error");

          if (res["status"] == "success") {
            $form.html(res["message"]);
            Swal.close();
          }
        })
        .fail(function () {
          showDialog("Could not proceed due to an anonymous error.", "error");
        });
    });

    $passwordRecoverEmail.on("input", function () {
      var $input = $(this),
        value = $input.val();

      $input.toggleInputError(
        value.length > 0 && !isEmail(value),
        "Email is not in valid format"
      );
      $passwordRecoverBtn.attr(
        "disabled",
        $input.hasClass("input-error") || $input.val() == ""
      );
    });
  }

  /** ---------------------------------------------------------------------
   * Change password script
   * ---------------------------------------------------------------------
   */

  if (elementExists("#reset-password-form")) {
    var $resetPasswordForm = $("#reset-password-form"),
      $newPassword = $("#new-password"),
      $confirmNewPassword = $("#confirm-new-password"),
      $resetPasswordBtn = $("#reset-password-btn"),
      passwordInputs = "#reset-password-form :input";

    $resetPasswordForm.on("submit", function (e) {
      e.preventDefault();

      var $form = $(this),
        formData = new FormData();

      processing("Please wait...");

      formData.append(
        "grt_reset_user_password",
        JSON.stringify({
          user_uid_token: $form.data("rft"),
          user_new_password: $newPassword.val(),
          request_page_token: $form.data("rt"),
          timestamp: $form.data("ts"),
        })
      );

      $.post({
        url: "/user/scripts/user_reset_password.php",
        data: formData,
        processData: false,
        contentType: false,
      })
        .done(function (response) {
          var res = JSON.parse(response);

          if (res["status"] == "error")
            return showDialog(res["message"], "error");

          if (res["status"] == "success") {
            $form.html(res["message"]);
            Swal.close();
          }
        })
        .fail(function () {
          showDialog("Could not proceed due to an anonymous error.", "error");
        });
    });

    $newPassword.on("input", function () {
      var $input = $(this),
        value = $input.val(),
        len = value.length,
        status = false,
        msg = "",
        confirmValue = $confirmNewPassword.val();

      if (len >= 1 && len < 8) {
        status = true;
        msg = "Must contain at least 8 characters";
      }

      $input.toggleInputError(status, msg);
      $confirmNewPassword
        .attr("disabled", !(len >= 8))
        .toggleInputError(
          !status &&
            len >= 1 &&
            value !== confirmValue &&
            confirmValue.length >= 1,
          "Password does not match"
        );
    });

    // Matching password
    $confirmNewPassword.on("input", function () {
      var $input = $(this),
        value = $input.val(),
        len = value.length,
        status = false;

      if (len >= 1 && value !== $newPassword.val()) status = true;

      $input.toggleInputError(status, "Password does not match");
    });

    $doc.on("input", passwordInputs, function () {
      var inputStatus = false;

      $(passwordInputs).each(function () {
        var $input = $(this);

        if ($input.hasClass("input-error") || $input.val() == "")
          inputStatus = true;
      });

      $resetPasswordBtn.attr("disabled", inputStatus);
    });
  }

  /** ---------------------------------------------------------------------
   * Resend email script
   * ---------------------------------------------------------------------
   */

  if (elementExists("#resend-email-btn")) {
    $("#resend-email-btn").on("click", function (e) {
      e.preventDefault();

      var formData = new FormData();

      formData.append(
        "grt_resend_email",
        JSON.stringify({
          user_first_name: currentUser.first_name,
          user_last_name: currentUser.last_name,
          user_uid_token: currentUser.token,
          user_email: currentUser.email,
        })
      );

      processing("Please wait...");

      $.post({
        url: "/user/scripts/user_resend_email.php",
        data: formData,
        processData: false,
        contentType: false,
      })
        .done(function (response) {
          var res = JSON.parse(response);

          showDialog(res["message"], res["status"]);
        })
        .fail(function () {
          showDialog("Could not proceed due to an anonymous error.", "error");
        });
    });
  }

  /** ---------------------------------------------------------------------
   * Changing user info script
   * ---------------------------------------------------------------------
   */

  if (elementExists("#settings")) {
    var $settings = $("#settings");

    $("#avatar-input").on("change", function () {
      var $input = $(this),
        file = $input.prop("files")[0];

      // Don't accept more than 1MB
      if (file.size / 1000 > 1024) {
        return showDialog(
          "This image size is too large, please select an image less than 1MB of size.",
          "error"
        );
      }

      if (!window.cropper) {
        window.croppedImage = undefined;
        Dialog({
          title: "Crop image",
          html: '<div id="cropped-avatar"><i class="fa fa-spinner fa-spin" id="thumb-loader"></i></div>',
          showCancelButton: true,
          confirmButtonText: '<i class="fa fa-scissors"></i> Crop',
          cancelButtonText: '<i class="fa fa-close"></i> Cancel',
          onOpen: function () {
            Swal.getConfirmButton().setAttribute("disabled", true);
            Swal.getCancelButton().setAttribute("disabled", true);
          },
        }).then(function (result) {
          if (result.value) {
            window.cropper
              .croppie("result", {
                type: "blob",
                format: "png",
              })
              .then(function (blob) {
                var url = window.URL || window.webkitURL,
                  img = blob;

                Dialog({
                  title: "Successfully Cropped",
                  imageUrl: url.createObjectURL(img),
                  allowOutsideClick: false,
                  confirmButtonText: '<i class="fa fa-save"></i> Save',
                  showCancelButton: true,
                  preConfirm: function () {
                    var formData = new FormData();

                    formData.append(
                      "grt_avatar_data",
                      JSON.stringify({
                        user_name: currentUser.name,
                        user_id: currentUser.id,
                        user_current_avatar: currentUser.avatar_url,
                      })
                    );

                    formData.append("avatar", img);
                    return $.ajax({
                      url: "/user/scripts/user_change_avatar.php",
                      type: "POST",
                      data: formData,
                      processData: false,
                      contentType: false,
                    }).done(function (response) {
                      var res = JSON.parse(response);

                      if (res["status"] == "error") {
                        Swal.showValidationMessage(res["message"]);
                      }
                    });
                  },
                }).then(function (result) {
                  if (result.value) {
                    var rsp = JSON.parse(result.value);
                    showDialog(rsp.message, "success").then(function () {
                      window.location.reload();
                    });
                  }
                });
                $input.clearFile();
                window.cropper = undefined;
              });
          } else {
            $input.clearFile();
            window.cropper = undefined;
          }
        });
        if (Swal.isVisible()) {
          setTimeout(function () {
            var fileReader = new FileReader();

            Swal.getConfirmButton().removeAttribute("disabled");
            Swal.getCancelButton().removeAttribute("disabled");
            fileReader.onload = function (e) {
              var cropper = $("#cropped-avatar");

              window.cropper = cropper.croppie({
                enableExif: true,
                viewport: {
                  height: 60,
                  width: 60,
                },
                boundary: {
                  height: 200,
                  width: 200,
                },
              });
              cropper.croppie("bind", {
                url: e.target.result,
              });
              $("#thumb-loader").css("opacity", "0");
            };
            fileReader.readAsDataURL(file);
          }, 1000);
        }
      }
    });

    // Removing avatar
    $("#remove-avatar").on("click", function () {
      Dialog({
        title: "Remove Avatar?",
        text: "Are you sure you want to remove your avatar?",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: '<i class="fa fa-trash"></i> Remove',
        cancelButtonText: '<i class="fa fa-close"></i> Cancel',
        preConfirm: function () {
          var formData = new FormData();

          formData.append(
            "grt_avatar_data",
            JSON.stringify({
              user_name: currentUser.name,
              user_id: currentUser.id,
              user_current_avatar: currentUser.avatar_url,
            })
          );

          return $.ajax({
            url: "/user/scripts/user_change_avatar.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
          }).done(function (response) {
            var res = JSON.parse(response);

            if (res["status"] == "error") {
              Swal.showValidationMessage(res["message"]);
            }
          });
        },
      }).then(function (result) {
        if (result.value) {
          var rsp = JSON.parse(result.value);
          showDialog(rsp.message, "success").then(function () {
            window.location.reload();
          });
        }
      });
    });

    $("#change-email").on("click", function () {
      var markup = `
        <div class="input-group mt-2">
				  <input 
            type="email" 
            class="input" 
            id="new-email" 
            value="${currentUser.email}"
            placeholder="New Email" 
            autocomplete="off" 
            autocapitalize="off" />
			  </div>`;

      Dialog({
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-save"></i> Save',
        cancelButtonText: '<i class="fa fa-close"></i> Cancel',
        html: markup,
        allowOutsideClick: false,
        showLoaderOnConfirm: true,
        preConfirm: function () {
          var newEmail = $("#new-email").val();

          if (newEmail == "")
            return Swal.showValidationMessage(
              "Please type your new email address."
            );

          if (
            !/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(
              newEmail
            )
          )
            return Swal.showValidationMessage("Email is not in valid format.");

          var formData = new FormData();

          formData.append(
            "grt_change_user_email",
            JSON.stringify({
              user_new_email: newEmail,
              csrf_token: $settings.data("token"),
            })
          );

          return $.post({
            url: "/user/scripts/user_change_email.php",
            data: formData,
            processData: false,
            contentType: false,
          })
            .done(function (response) {
              var res = JSON.parse(response);

              if (res["status"] == "error") {
                Swal.showValidationMessage(res["message"]);
                Swal.hideLoading();
              }
            })
            .fail(function () {
              Swal.showValidationMessage(
                "Could not proceed due to an anonymous error."
              );
              Swal.hideLoading();
            });
        },
      }).then(function (result) {
        if (result.value) {
          var rsp = JSON.parse(result.value);
          Dialog({
            title: "Check your Email",
            html: rsp.message,
            allowOutsideClick: false,
          });
        }
      });
    });

    $doc.on("input", "#new-email", function () {
      if (Swal.isVisible() && Swal.getValidationMessage())
        Swal.resetValidationMessage();
    });

    // Changing password
    $("#change-password").on("click", function (e) {
      e.preventDefault();

      var markup = `
        <div class="input-group mt-2">
				  <input 
            type="password" 
            class="input" 
            id="current-password" 
            placeholder="Current Password" 
            autocomplete="off" 
            autocapitalize="off" />
			   </div>
			   <div class="input-group">
				  <input 
            type="password" 
            class="input" 
            id="new-password" 
            placeholder="New Password" 
            autocomplete="off" 
            autocapitalize="off" />
			   </div>
			   <div class="input-group">
				  <input 
            type="password" 
            class="input" 
            id="confirm-new-password" 
            placeholder="Confirm Password" 
            autocomplete="off" 
            autocapitalize="off" />
			   </div>`;

      Dialog({
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-save"></i> Save',
        cancelButtonText: '<i class="fa fa-close"></i> Cancel',
        html: markup,
        allowOutsideClick: false,
        preConfirm: function () {
          var currentPass = $("#current-password").val(),
            newPass = $("#new-password").val(),
            confirmPass = $("#confirm-new-password").val();

          if (currentPass == "" || newPass == "" || confirmPass == "")
            return Swal.showValidationMessage(
              "Please fill all the inputs properly."
            );

          if (newPass.length < 8)
            return Swal.showValidationMessage(
              "Your new password must contain at least 8 characters."
            );

          if (confirmPass != newPass)
            return Swal.showValidationMessage(
              "Your new password did not match."
            );

          var formData = new FormData();

          formData.append(
            "grt_change_user_password",
            JSON.stringify({
              user_current_password: currentPass,
              user_new_password: newPass,
              csrf_token: $settings.data("token"),
            })
          );

          return $.post({
            url: "/user/scripts/user_change_password.php",
            data: formData,
            processData: false,
            contentType: false,
          })
            .done(function (response) {
              var res = JSON.parse(response);

              if (res["status"] == "error") {
                Swal.showValidationMessage(res["message"]);
                Swal.hideLoading();
              }
            })
            .fail(function () {
              Swal.showValidationMessage(
                "Could not proceed due to an anonymous error."
              );
              Swal.hideLoading();
            });
        },
      }).then(function (result) {
        if (result.value) {
          var rsp = JSON.parse(result.value);

          showDialog(rsp.message, "success");
        }
      });
    });

    $doc.on(
      "input",
      "#current-password,#new-password,#confirm-new-password",
      function () {
        if (Swal.isVisible() && Swal.getValidationMessage())
          Swal.resetValidationMessage();
      }
    );
  }

  /** ---------------------------------------------------------------------
   * Mentors script
   * ---------------------------------------------------------------------
   */

  // Mentors list
  if (elementExists("#mentors-list")) {
    var $mentorsList = $("#mentors-list");

    function loadMentorsList() {
      $.get("/user/scripts/mentors/get_list.php")
        .done(function (res) {
          renderMentorsList(JSON.parse(res));
        })
        .fail(function () {
          $mentorsList.failedToLoad(
            "Could not load due to an anonymous error."
          );
        });
    }

    function renderMentorsList(data) {
      if (!data.length) return $mentorsList.emptyList();

      $mentorsList
        .attr({
          class: "txt-grey",
        })
        .html(
          'Here is a list of available mentors<p class="txt-sm txt-dark-grey mt-1">You can select a mentor to start the trading session with</p>'
        );

      for (var i = 0, j = data.length; i < j; i++) {
        var mentor = data[i];

        var row = $("<div>", {
          class: "flex flex-centered flex-no-break my-2",
        }).appendTo($mentorsList);

        var avatar = $("<div>", {
          class: "avatar avatar-md",
        }).appendTo(row);

        if (mentor.avatar_url != "none") {
          $("<img>", {
            class: "avatar-img",
            src: `/media/admin-avatars/${mentor.avatar_url}`,
            alt: `${mentorName}'s avatar`,
          }).appendTo(avatar);
        } else {
          $("<div>", {
            class: "avatar-no-img",
            style: `background-color: ${mentor.avatar_color}`,
          })
            .text(mentor.first_letter_of_name)
            .appendTo(avatar);
        }

        var info = $("<div>", {
          class: "ml-1",
        }).appendTo(row);

        $("<h4>").text(mentor.name).appendTo(info);

        var selectButton = $("<button>", {
          id: "select-mentor",
          class: "btn btn-silver btn-sm txt-uppercase mt-1",
          title: `Select ${mentor.name} as your mentor`,
          "data-mentor-id": mentor.id,
          "data-mentor-name": mentor.name,
        })
          .text("Select")
          .appendTo(info);

        if (currentUser.mentorID !== "0") {
          if (currentUser.mentorID == mentor.id) {
            $("<small>")
              .html(
                'is your current mentor. <i class="fa fa-check txt-success"></i>'
              )
              .appendTo(info);

            selectButton.hide();
          } else {
            row.css("opacity", ".3");
            selectButton.attr("disabled", true);
          }
        }
      }
    }

    $doc.on("click", "#select-mentor", function () {
      var $btn = $(this),
        mentorName = $btn.data("mentor-name"),
        mentorID = $btn.data("mentor-id");

      Dialog({
        html: `Select ${mentorName} as your mentor?`,
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-check"></i> Select',
        cancelButtonText: '<i class="fa fa-close"></i> Cancel',
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        preConfirm: function () {
          return $.post({
            url: "/user/scripts/mentors/assign.php",
            data: {
              grt_assign_mentor_data: JSON.stringify({
                mentor_id: mentorID,
                mentor_name: mentorName,
                csrf_token: $mentorsList.data("token"),
              }),
            },
          })
            .done(function (response) {
              var res = JSON.parse(response);

              if (res["status"] == "error")
                showDialog(res["message"], res["status"]);
            })
            .fail(function () {
              showDialog(
                "Could not proceed due to an anonymous error.",
                "error"
              );
            });
        },
      }).then(function (res) {
        if (res.value) {
          var rsp = JSON.parse(res.value);

          showDialog(rsp.message, "success").then(function () {
            currentUser.mentorID = mentorID;
            loadMentorsList();
          });
        }
      });
    });
    loadMentorsList();
  }

  /* ----------------------------------------------------------------------------
   * Quote script
   * ----------------------------------------------------------------------------
   */

  if (elementExists("#quote-form")) {
    var $quoteAuthorFirstName = $("#quote-author-firstname"),
      $quoteAuthorLastName = $("#quote-author-lastname"),
      $quoteText = $("#quote-text"),
      $quoteAddBtn = $("#add-quote-btn"),
      $quoteCount = $("#quote-count"),
      $quoteList = $("#quote-list"),
      MAX_QUITE_LIMIT = 50;

    $("#quote-form").on("submit", function (e) {
      e.preventDefault();

      if (w._TOTAL_QUOTES && w._TOTAL_QUOTES == MAX_QUITE_LIMIT)
        return showDialog("Maximum quotes limit has reached.", "error");

      var $form = $(this),
        formData = new FormData();

      formData.append(
        "grt_quote_data",
        JSON.stringify({
          quote_text: $quoteText.val(),
          quote_author: `${$quoteAuthorFirstName.val()} ${$quoteAuthorLastName.val()}`,
          csrf_token: $form.data("token"),
        })
      );

      processing("Please wait...");

      $.post({
        url: "/user/scripts/quotes/create.php",
        data: formData,
        processData: false,
        contentType: false,
      })
        .done(function (response) {
          var res = JSON.parse(response);

          if (res["status"] == "error")
            return showDialog(res["message"], "error");

          if (res["status"] == "success") {
            showDialog(res["message"], "success").then(function () {
              $form.find(":input").val("");
              $quoteText.val("");
              $quoteAddBtn.attr("disabled", true);
              loadQuoteList();
            });
          }
        })
        .fail(function () {
          showDialog("Could not add quote due to an anonymous error.", "error");
        });
    });

    $doc.on(
      "input",
      "#quote-author-firstname,#quote-author-lastname",
      function () {
        var $input = $(this),
          value = $input.val(),
          msg = "",
          status = false,
          len = value.length;

        if (len >= 1 && len < 2) {
          msg = "Should not be less than 2 characters";
          status = true;
        }

        if (len > 10) {
          msg = "Should not be more than 10 characters";
          status = true;
        }

        if (!/^[a-zA-Z]*$/.test(value)) {
          msg = "Numbers, spaces or symbols are not allowed";
          status = true;
        }

        $input.toggleInputError(status, msg);
      }
    );

    $doc.on("input", "#quote-text", function () {
      var $input = $(this),
        value = $input.val(),
        msg = "",
        status = false,
        len = value.length;

      if (len >= 1 && len < 10) {
        msg = "Should not be less than 10 characters";
        status = true;
      }

      if (/[`!@#$%^&*()_+\-=\[\]{};':"\\|<>\/~0-9]/.test(value)) {
        msg =
          "No symbols allowed except commas, periods, exclamation and question marks";
        status = true;
      }

      $input.toggleInputError(status, msg);
    });

    $doc.on(
      "input",
      "#quote-author-firstname,#quote-author-lastname,#quote-text",
      function () {
        var toggleButton = false;

        $("#quote-author-firstname,#quote-author-lastname,#quote-text").each(
          function () {
            var $input = $(this);

            if ($input.hasClass("input-error") || $input.val() == "")
              toggleButton = true;
          }
        );

        $quoteAddBtn.attr("disabled", toggleButton);
      }
    );

    function loadQuoteList() {
      $.get("/user/scripts/quotes/read.php")
        .done(function (response) {
					console.log(response);
          renderQuoteList(JSON.parse(response));
        })
        .fail(function () {
          $quoteList.failedToLoad("Could not load due to an anonymous error.");
        });
    }

    function renderQuoteList(data) {
      if (!data || !data["data"]) return $quoteList.emptyList();

      $quoteList.html("");

      var list = data["data"],
        total = data["total"],
        table = createTable({
          parent: $quoteList,
          headers: ["ID", "Quote", "Author", "Action"],
        });

      w._TOTAL_NUMBERS_OF_QUOTES = total;

      for (var i = 0, j = list.length; i < j; i++) {
        var quote = list[i],
          rows = $("<tr>", {
            id: quote.id,
          }).appendTo(table);

        $("<td>").text(quote.id).appendTo(rows);

        $("<td>").text(quote.author).appendTo(rows);

        $("<td>").text(quote.text).appendTo(rows);

        var actionColumn = $("<td>", {
          class: "align-center",
        }).appendTo(rows);

        // Delete button
        $("<i>")
          .attr({
            class: "fa fa-trash icon-btn icon-btn-danger",
            title: "Delete this quote",
            id: "delete-quote-btn",
            "data-delete-quote-id": quote.id,
          })
          .appendTo(actionColumn);
      }

      $quoteCount
        .attr({
          class: "txt-dark-grey txt-thin",
        })
        .text(`(${total}/${MAX_QUITE_LIMIT})`);
    }

    // Deleting the quote (triggered when user clicks delete button in the table)
    $doc.on("click", "#delete-quote-btn", function () {
      var quoteID = $(this).data("delete-quote-id");

      Dialog({
        title: "Delete quote?",
        text: "This action permanently deletes this quote from the database and cannot be undone.",
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-trash"></i> Delete',
        cancelButtonText: '<i class="fa fa-close"></i> Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function () {
          var formData = new FormData();

          formData.append(
            "grt_quote_delete_data",
            JSON.stringify({
              quote_id: quoteID,
              csrf_token: $quoteList.data("token"),
            })
          );

          return $.post({
            url: "/user/scripts/quotes/delete.php",
            data: formData,
            processData: false,
            contentType: false,
          })
            .done(function (response) {
              var res = JSON.parse(response);

              if (res["status"] == "error") showDialog(res["message"], "error");
            })
            .fail(function () {
              showDialog(
                "Could not proceed due to an anonumouse error.",
                "error"
              );
            });
        },
      }).then(function (res) {
        if (res.value) {
          var rsp = JSON.parse(res.value);

          showDialog(rsp.message, "success");
          $quoteCount.text(`${w._TOTAL_QUOTES}/${MAX_QUITE_LIMIT}`);
          $("#" + quoteID).remove();
          w._TOTAL_QUOTES -= 1;
        }
      });
    });

    loadQuoteList();
  }

  /* ----------------------------------------------------------------------------
   * Activity log script
   * ----------------------------------------------------------------------------
   */

  $.fn.loadLogs = function (type) {
    var $parent = $(this);

    $.get("/user/scripts/get_logs.php?type=" + type)
      .done(function (response) {
        $parent.renderLogList(JSON.parse(response));
      })
      .fail(function () {
        $parent.failedToLoad("Could not load due to an anonymous error.");
      });
  };

  $.fn.renderLogList = function (data) {
    var $parent = $(this);

    if (!data.length) return $parent.emptyList();

    $parent.html("");

    for (var i = 0, j = data.length; i < j; i++) {
      var log = data[i];

      $("<p>", {
        class: "log-text",
      })
        .html(`[${log.timestamp}] ${log.text}`)
        .appendTo($parent);
    }
  };

  // Get admin activity log list
  if (elementExists("#admin-log-list")) {
    $("#admin-log-list").loadLogs(0);
  }

  // Get user activity log list
  if (elementExists("#user-log-list")) {
    $("#user-log-list").loadLogs(1);
  }

  /* ----------------------------------------------------------------------------
   * Payment list script
   * ----------------------------------------------------------------------------
   */

  // Fetching users list
  if (elementExists("#payment-list")) {
    var $paymentList = $("#payment-list");

    function loadPaymentList() {
      $.get("/user/scripts/payments/get_list.php")
        .done(function (response) {
          renderPaymentList(JSON.parse(response));
        })
        .fail(function () {
          $paymentList.failedToLoad(
            "Could not load due to an anonymous error."
          );
        });
    }

    function refreshPaymentList() {
      $paymentList.html("");
      return loadPaymentList();
    }

    function renderPaymentList(data) {
      if (!data.length) return $paymentList.emptyList();

      $paymentList
        .attr({
          class: "txt-grey txt-mds",
        })
        .html(
          'All the payments made by users at GoldenRules<p class="txt-dark-grey txt-sm"><i class="fa fa-exclamation-circle"></i> The table row being greyed out means that the payment has already been denied or approved and an action cannot be taken on it.</p>'
        );

      var table = createTable({
        parent: $paymentList,
        headers: [
          "Tran. ID",
          "Payer",
          "Email",
          "Amount",
          "Paid",
          "Status",
          "Action",
        ],
      });

      for (var i = 0, j = data.length; i < j; i++) {
        var payment = data[i],
          status = payment.status,
          rows = $("<tr>", {
            id: payment.id,
            class: status == "APPROVED" || status == "DENIED" ? "disabled" : "",
          }).appendTo(table);

        $("<td>").text(payment.transaction_id).appendTo(rows);

        $("<td>").text(payment.customer_name).appendTo(rows);
        $("<td>").text(payment.customer_email).appendTo(rows);

        $("<td>").text(`$${payment.amount}`).appendTo(rows);

        $("<td>").text(payment.date).appendTo(rows);

        $("<td>", {
          class: "txt-uppercase",
        })
          .text(status)
          .appendTo(rows);

        if (status == "PENDING") {
          var actionColumn = $("<td>").appendTo(rows);

          // Delete button
          $("<i>")
            .attr({
              class: "fa fa-check icon-btn icon-btn-success",
              title: "Approve this payment",
              id: "approve-payment-btn",
              "data-approve-payment-id": payment.id,
              "data-approve-payment-trans-id": payment.transaction_id,
              "data-approve-payment-date": payment.date,
              "data-approve-payment-amount": payment.amount,
              "data-approve-payment-customer-email": payment.customer_email,
              "data-approve-payment-customer-username":
                payment.customer_username,
              "data-approve-payment-customer-name": payment.customer_name,
              "data-approve-payment-customer-id": payment.customer_id,
            })
            .text("approve")
            .appendTo(actionColumn);

          // Edit button
          $("<i>")
            .attr({
              class: "fa fa-times icon-btn icon-btn-danger",
              title: "Deny this payment",
              id: "deny-payment-btn",
              "data-deny-payment-id": payment.id,
              "data-deny-payment-trans-id": payment.transaction_id,
              "data-deny-payment-amount": payment.amount,
              "data-deny-payment-date": payment.date,
              "data-deny-payment-customer-email": payment.customer_email,
              "data-deny-payment-customer-name": payment.customer_name,
              "data-deny-payment-customer-username": payment.customer_username,
            })
            .text("deny")
            .appendTo(actionColumn);
        } else {
          $("<td>").appendTo(rows);
        }
      }
    }

    // Approve payment dialog
    $doc.on("click", "#approve-payment-btn", function () {
      var $btn = $(this);

      Dialog({
        title: "Approve payment?",
        html: "Once approved, the user will be given the paid membership access and they will be notified via email.",
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-check"></i> Approve',
        cancelButtonText: '<i class="fa fa-times"></i> Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function () {
          var formData = new FormData();

          formData.append(
            "grt_payment_approve_data",
            JSON.stringify({
              payment_id: $btn.data("approve-payment-id"),
              payment_transaction_id: $btn.data("approve-payment-trans-id"),
              payment_amount: $btn.data("approve-payment-amount"),
              payment_date: $btn.data("approve-payment-date"),
              payment_payer_name: $btn.data("approve-payment-customer-name"),
              payment_payer_username: $btn.data(
                "approve-payment-customer-username"
              ),
              payment_payer_email: $btn.data("approve-payment-customer-email"),
              payment_payer_id: $btn.data("approve-payment-customer-id"),
              csrf_token: $paymentList.data("token"),
            })
          );

          return $.post({
            url: "/user/scripts/payments/approve.php",
            data: formData,
            processData: false,
            contentType: false,
          })
            .done(function (response) {
              var res = JSON.parse(response);

              if (res["status"] == "error") showDialog(res["message"], "error");
            })
            .fail(function () {
              showDialog(
                "Could not proceed due to an anonymous error.",
                "error"
              );
            });
        },
      }).then(function (result) {
        if (result.value) {
          var rsp = JSON.parse(result.value);

          showDialog(rsp.message, "success");
          refreshPaymentList();
        }
      });
    });

    // Deny payment dialog
    $doc.on("click", "#deny-payment-btn", function (e) {
      var $btn = $(this);

      Dialog({
        title: "Deny payment?",
        html: "If denied, the user will not be given the paid membership access and they will be notified via email.",
        input: "textarea",
        inputClass: "textarea",
        inputAttributes: {
          autocapitalize: "off",
          placeholder: "Give a reason for denying this payment...",
        },
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-ban"></i> Deny',
        cancelButtonText: '<i class="fa fa-times"></i> Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function (reason) {
          if (!reason.length)
            return Swal.showValidationMessage("Please give a reason.");

          if (reason.length < 15)
            return Swal.showValidationMessage(
              "Reason must be at least 15 characters long."
            );

          var formData = new FormData();

          formData.append(
            "grt_payment_deny_data",
            JSON.stringify({
              payment_id: $btn.data("deny-payment-id"),
              payment_transaction_id: $btn.data("deny-payment-trans-id"),
              payment_amount: $btn.data("deny-payment-amount"),
              payment_date: $btn.data("deny-payment-date"),
              payment_payer_name: $btn.data("deny-payment-customer-name"),
              payment_payer_username: $btn.data(
                "deny-payment-customer-username"
              ),
              payment_payer_email: $btn.data("deny-payment-customer-email"),
              payment_deny_reason: reason,
              csrf_token: $paymentList.data("token"),
            })
          );

          return $.post({
            url: "/user/scripts/payments/deny.php",
            data: formData,
            processData: false,
            contentType: false,
          })
            .done(function (response) {
              var res = JSON.parse(response);

              if (res["status"] == "error") showDialog(res["message"], "error");
            })
            .fail(function () {
              showDialog(
                "Could not proceed due to an anonymous error.",
                "error"
              );
            });
        },
      }).then(function (result) {
        if (result.value) {
          var rsp = JSON.parse(result.value);

          showDialog(rsp.message, "success");
          refreshPaymentList();
        }
      });
    });

    loadPaymentList();
  }

  /* ----------------------------------------------------------------------------
   * Students and mentors script
   * ----------------------------------------------------------------------------
   */

  // Fetching users list
  if (elementExists("#students-list")) {
    var $studentsList = $("#students-list");

    function loadStudentsList() {
      $.get("/user/scripts/students/get_list.php?mentor_id=" + currentUser.id)
        .done(function (response) {
          renderStudentsList(JSON.parse(response));
        })
        .fail(function () {
          $studentsList.failedToLoad(
            "Could not load due to an anonymous error."
          );
        });
    }

    function renderStudentsList(data) {
      if (!data.length) return $studentsList.emptyList();

      $studentsList
        .attr({
          class: "txt-grey",
        })
        .html(
          `${currentUser.first_name}, here is a list of students you currently have trading sessions with`
        );

      for (var i = 0, j = data.length; i < j; i++) {
        var student = data[i],
          row = $("<div>", {
            class: "flex flex-centered flex-no-break my-2",
          }).appendTo($studentsList);

        var avatar = $("<div>", {
          class: "avatar avatar-md",
        }).appendTo(row);

        $("<div>", {
          class: "avatar-no-img",
          style: `background-color: ${student.avatar_color}`,
        })
          .text(student.first_letter)
          .appendTo(avatar);

        var info = $("<div>", {
          class: "ml-1",
        }).appendTo(row);

        $("<strong>")
          .text(student.name + " - ")
          .appendTo(info);

        $("<a>", {
          id: "end-session-btn",
          class: "txt-sm txt-danger-light txt-uppercase link-no-underline",
          title: `Click to end session with ${student.name}`,
          href: "javascript:",
          "data-student-id": student.id,
          "data-student-name": student.name,
        })
          .text("end session")
          .appendTo(info);
      }
    }

    // Ending session
    $doc.on("click", "#end-session-btn", function () {
      var $btn = $(this);

      Dialog({
        title: "End session?",
        text: "Once ended, you will no longer be their mentor.",
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-stop"></i> End',
        cancelButtonText: '<i class="fa fa-times"></i> Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function () {
          return $.post({
            url: "/user/scripts/students/end_session.php",
            data: {
              grt_end_session_data: JSON.stringify({
                student_id: $btn.data("student-id"),
                student_name: $btn.data("student-name"),
                csrf_token: $studentsList.data("token"),
              }),
            },
          })
            .done(function (response) {
              var res = JSON.parse(response);

              if (res["status"] == "error") showDialog(res["message"], "error");
            })
            .fail(function () {
              showDialog(
                "Could not proceed due to an anonymous error",
                "error"
              );
            });
        },
      }).then(function (result) {
        if (result.value) {
          var rsp = JSON.parse(result.value);

          showDialog(rsp.message, "success").then(function () {
            w.location.reload();
          });
        }
      });
    });

    loadStudentsList();
  }
});
