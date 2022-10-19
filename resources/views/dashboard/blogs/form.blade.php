<div class="modal fade" id="BlogsModal">
  <div class="modal-dialog modal-fullscreen text-dark">
    <div class="modal-content">
      <div class="modal-content container" style="overflow-y:scroll;">
        <div class="row">
          <div class="col-12 text-end">
            <span type="button" class="btn-close" data-bs-dismiss="modal"></span>
          </div>
          <div class="col-12">
            <div id="BlogsErrorsContainer" class="alert alert-danger errorContainer" style="display:none;">
              <h5 class="font-weight-bolder">Error!</h5>
              <ul></ul>
            </div>
          </div>
          <div class="col-12">
            <hr>
            <h5 class="modal-title font-weight-bolder">New Blog</h5>
            <hr>
            <form id="BlogsForm">
              <input type="hidden" class="form-control" id="itemId">
              <div class="row">
                <div class="col-12 mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="blogsIsTrending">
                    <label class="form-check-label">Trending</label>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label font-weight-bolder">Title</label>
                  <input type="text" class="form-control" id="blogsTitle">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label font-weight-bolder">Date</label>
                  <input type="text" class="form-control" id="blogsDate">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label font-weight-bolder">Author Name</label>
                  <input type="text" class="form-control" id="blogsAuthorName">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label font-weight-bolder">Author Image URL</label>
                  <input type="text" class="form-control" id="blogsAuthorImageUrl">
                </div>
                <div class="col-12">
                  <hr>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label font-weight-bolder">Image Url Landscape</label>
                  <input type="text" class="form-control" id="blogsImageUrlLandscape">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                  <label class="form-label font-weight-bolder">Image Url Portrait</label>
                  <input type="text" class="form-control" id="blogsImageUrlPortrait">
                </div>
                <div class="col-12">
                  <hr>
                </div>
                <div class="col-12">
                  <label class="form-label font-weight-bolder">Tags</label>
                  <input type="text" class="form-control" id="blogsTags">
                  <div class="form-text">Tags must be seperated by a comma. Example: <i>Laravel, Hosting</i></div>
                </div>
                <div class="col-12">
                  <hr>
                </div>
                <div class="col-12">
                  <label class="form-label font-weight-bolder">Description</label>
                  <textarea rows="2" type="text" class="form-control" id="blogsDescription"></textarea>
                  <div class="form-text">1-3 sentences max.</div>
                </div>
                <div class="col-12 mb-5">
                  <label class="form-label font-weight-bolder">Content</label>
                  <div id="blogsContent"></div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer container">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
        <button id="SubmitBlogsForm" type="button" class="btn btn-sm btn-primary">Save Changes</button>
      </div>
    </div>
  </div>
</div>
<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
  let formSubmitted = false;
  let blogsEndpoint = "/api/dashboard/blogs";
  let quillToolbar = [
    ['bold', 'italic', 'underline', 'strike'], // toggled buttons
    ['blockquote', 'code-block'],

    [{
      'header': 1
    }, {
      'header': 2
    }], // custom button values
    [{
      'list': 'ordered'
    }, {
      'list': 'bullet'
    }],
    [{
      'script': 'sub'
    }, {
      'script': 'super'
    }], // superscript/subscript
    [{
      'indent': '-1'
    }, {
      'indent': '+1'
    }], // outdent/indent
    [{
      'direction': 'rtl'
    }], // text direction

    [{
      'size': ['small', false, 'large', 'huge']
    }], // custom dropdown
    [{
      'header': [1, 2, 3, 4, 5, 6, false]
    }],

    [{
      'color': []
    }, {
      'background': []
    }], // dropdown with defaults from theme
    [{
      'font': []
    }],
    [{
      'align': []
    }],

    ['clean'] // remove formatting button
  ];
  let quill = new Quill(
    "#blogsContent", {
      modules: {
        toolbar: quillToolbar
      },
      placeholder: "Compose an epic ...",
      theme: "snow"
    }
  );

  function clearForm() {
    $("#itemId").val(null);
    $("#blogsTitle").val(null);
    $("#blogsAuthorName").val(null),
      $("#blogsAuthorImageUrl").val(null);
    $("#blogsImageUrlLandscape").val(null);
    $("#blogsImageUrlPortrait").val(null);
    $("#blogsTags").val(null);
    $("#blogsDescription").val(null);
    $("#blogsDate").val(null);
    $("#blogsIsTrending").prop("checked", false);
    $("#blogsContent").val(null);
    quill.setContents([{
      insert: "\n"
    }]);
    clearErrors();
  }

  function clearErrors() {
    $("#BlogsErrorsContainer ul").empty();
    $("#BlogsErrorsContainer").hide();
  }

  function showErrors(errorsList = []) {
    Object.keys(errorsList).forEach(key => {
      $("#BlogsModal .errorContainer ul").append(
        "<li><b>" + key + ": </b>" + errorsList[key] + "</li>"
      );
      $("#BlogsModal .errorContainer").show();
    });
  }

  $(document).ready(function() {
    $("#SubmitBlogsForm").click(function() {
      event.preventDefault();
      clearErrors();

      if (formSubmitted !== true) {
        formSubmitted = true;
        $.ajax({
          type: "POST",
          url: blogsEndpoint,
          dataType: "json",
          contentType: "application/json; charset=utf-8",
          data: JSON.stringify({
            id: $("#itemId").val(),
            title: $("#blogsTitle").val(),
            author: $("#blogsAuthorName").val(),
            author_image_url: $("#blogsAuthorImageUrl").val(),
            image_url_landscape: $("#blogsImageUrlLandscape").val(),
            image_url_portrait: $("#blogsImageUrlPortrait").val(),
            tags: $("#blogsTags").val(),
            description: $("#blogsDescription").val(),
            date: $("#blogsDate").val(),
            is_trending: $("#blogsIsTrending").prop("checked"),
            content: quill.root.innerHTML.trim(),
          }),
          success: function(response) {
            formSubmitted = false;
            $BlogsModal.hide();
            $("#dataTable").DataTable().ajax.reload();
          }
        }).fail(function(response) {
            formSubmitted = false;
          if (response.status === 422) {
            showErrors(response.responseJSON["errors"]);
          } else {
            showErrors({
              Error: "Could not process your request! Please try again later."
            });
          }
        });
      }

    });
  });
</script>
