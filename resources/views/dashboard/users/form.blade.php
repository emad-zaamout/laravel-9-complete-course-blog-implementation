<div class="modal fade" id="UsersModal">
    <div class="modal-dialog modal-fullscreen text-dark">
        <div class="modal-content">
            <div class="modal-content container" style="overflow-y:scroll;">
                <div class="row">
                    <div class="col-12 text-end">
                        <span type="button" class="btn-close" data-bs-dismiss="modal"></span>
                    </div>
                    <div class="col-12">
                        <div id="UsersErrorContainer" class="alert alert-danger errorContainer" style="display:none;">
                            <h5 class="font-weight-bolder">Error!</h5>
                            <ul></ul>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                        <h5 class="modal-title font-weight-bolder">New User</h5>
                        <hr>
                        <form id="UsersForm">
                            <input type="hidden" class="form-control" id="itemId">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label font-weight-bolder">Name</label>
                                    <input type="text" class="form-control" id="userName">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label font-weight-bolder">Email</label>
                                    <input type="text" class="form-control" id="userEmail">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label font-weight-bolder">Password</label>
                                    <input type="password" class="form-control" id="password" autocomplete="new-password">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="form-label font-weight-bolder">Password</label>
                                    <input type="password" class="form-control" id="passwordConfirmation" autocomplete="new-password">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer container">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="SubmitUsersForm" type="button" class="btn btn-sm btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    let formSubmitted = false;
    let usersEndpoint = "/api/dashboard/users";

    function clearForm() {
        $("#itemId").val(null);
        $("#userName").val(null);
        $("#userEmail").val(null);
        $("#password").val(null);
        $("#passwordConfirmation").val(null);
        clearErrors();
    }

    function clearErrors() {
        $("#UsersErrorContainer ul").empty();
        $("#UsersErrorContainer").hide();
    }

    function showErrors(errorsList = []) {
        Object.keys(errorsList).forEach(key => {
            $("#UsersModal .errorContainer ul").append(
                "<li><b>" + key + ": </b>" + errorsList[key] + "</li>"
            );
            $("#UsersModal .errorContainer").show();
        });
    }

    $(document).ready(function () {
        $("#SubmitUsersForm").click(function () {
            event.preventDefault();
            clearErrors();

            if (formSubmitted !== true) {
                formSubmitted = true;
                $.post(
                    usersEndpoint,
                    {
                        id: $("#itemId").val(),
                        name: $("#userName").val(),
                        email: $("#userEmail").val(),
                        id: $("#itemId").val(),
                        password: $("#password").val(),
                        password_confirmation: $("#passwordConfirmation").val(),
                    },
                    function(response) {
                        formSubmitted = false;
                        $UsersModal.hide();
                        $("#dataTable").DataTable().ajax.reload();
                    }
                ).fail(function(response) {
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
