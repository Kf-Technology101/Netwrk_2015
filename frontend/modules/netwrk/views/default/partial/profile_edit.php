<?php
    use yii\helpers\Url;
?>
<!--<div class="modal modal-profile" id='modal_profile_edit'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="header">
                    <div class="back-page">
                        <span><i class="fa fa-arrow-circle-left"></i> Back </span>
                    </div>
                    <div class="title-page">
                        <span class="title">Edit profile </span>
                    </div>
                </div>
            </div>
            <div class="modal-body profile-edit-wrapper">
                <form class="form-profile-edit">

                </form>
            </div>
        </div>
    </div>
</div>-->

<div id="profile_edit_slider">
    <div class="slider-profile" id='modal_profile_edit'>
        <div class="slider-dialog">
            <div class="slider-content">
                <div class="slider-header">
                    <div class="header">
                        <div class="title-page">
                            <span class="title">Edit Profile</span>
                            <span class="slider-close-btn"><i class="fa fa-close"></i></span>
                        </div>
                    </div>
                </div>
                <div class="slider-body">
                    <div class="profile-edit-wrapper">
                        <form class="form-profile-edit">

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="profile_edit" type="text/x-underscore-template">
    <div class="form-group-wrapper clearfix">
        <div class="form-group">
            <label for="firstName">First name</label>
            <input name="first_name" type="text" class="form-control" id="firstName" placeholder="First name" value="<%= data.first_name %>">
        </div>

        <div class="form-group">
            <label for="lastName">Last name</label>
            <input name="last_name" type="text" class="form-control" id="lastName" placeholder="Last name" value="<%= data.last_name %>">
        </div>
    </div>

    <div class="form-group-wrapper clearfix">
        <div class="form-group">
            <label for="userName">Username</label>
            <input name="user_name" type="text" class="form-control" id="userName" placeholder="Username" value="<%= data.user_name %>" disabled="disabled">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input name="email" type="email" class="form-control" id="email" placeholder="Email"  value="<%= data.email %>" disabled="disabled">
        </div>
    </div>

    <div class="form-group-wrapper clearfix">
        <div class="form-group">
            <label for="gender">Gender</label>
            <div class="radio_gender">
                <input type="radio" class="input_radio" name="gender" id="profileEditMale" value="Male"><label for="profileEditMale"> Male </label>
                <input type="radio" class="input_radio" name="gender" id="profileEditFemale" value="Female"><label for="profileEditFemale"> Female</label>
            </div>
        </div>

        <div class="form-group">
            <label for="homeZipCode">Home zip code</label>
            <input name="zip" type="text" class="form-control home_zip_code" id="homeZipCode" placeholder="Home zip code"  value="<%= data.zip %>">
        </div>
    </div>

    <div class="form-group-wrapper clearfix">
        <div class="form-group">
            <label for="dob">Date of birth</label>
            <input name="dob" type="text" class="form-control dob" id="dob" placeholder="Date of birth"  value="<%= data.dob %>">
        </div>

        <div class="form-group">
            <label for="maritalStatus">Marital status</label>
            <div class="dropdown input-group marital-status">
                <input type="text" id="maritalStatus" class="form-control dropdown marital-status-dropdown" name="marital_status"
                       placeholder="Marital Status" data-toggle="dropdown" aria-expanded="false" value="<%= data.marital_status %>">
                <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                <ul class="dropdown-menu" aria-labelledby="maritalStatus">
                    <li data-value="Single">Single</li>
                    <li data-value="Married">Married</li>
                    <li data-value="Divorced">Divorced</li>
                    <li data-value="Complicated">Its complicated</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="form-group-wrapper clearfix">
        <div class="form-group">
            <label for="work">Work</label>
            <input name="work" type="text" class="form-control" id="work" placeholder="Work"  value="<%= data.work %>">
        </div>

        <div class="form-group">
            <label for="education">Education</label>
            <input name="education" type="text" class="form-control" id="education" placeholder="Education"  value="<%= data.education %>">
        </div>
    </div>

    <div class="form-group-wrapper clearfix">
        <div class="form-group">
            <label for="country">Country</label>
            <input name="country" type="text" class="form-control" id="country" placeholder="Country"  value="<%= data.country %>" disabled="disabled">
            <!--<div class="dropdown input-group">
                <div class="dropdown-toggle disabled" type="button" id="country" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><%= data.country %></div>
                <span class="input-group-addon disabled" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                <ul class="dropdown-menu" aria-labelledby="country">
                    <li data-value="USA">USA</li>
                </ul>
            </div>-->
        </div>

        <div class="form-group">
            <label for="state">State</label>
            <input name="state" type="text" class="form-control" id="state" placeholder="State"  value="<%= data.state %>" disabled="disabled">
            <!--<div class="dropdown input-group">
                <div class="dropdown-toggle disabled" type="button" id="state" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><%= data.state %></div>
                <span class="input-group-addon disabled" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                <ul class="dropdown-menu" aria-labelledby="state">
                    <li data-value="Single">Indiana Police</li>
                </ul>
            </div>-->
        </div>
    </div>

    <div class="form-group-wrapper clearfix">
        <div class="form-group">
            <label for="city">City</label>
            <input name="city" type="text" class="form-control" id="city" placeholder="City"  value="<%= data.city %>" disabled="disabled">
            <!--<div class="dropdown input-group">
                <div class="dropdown-toggle" type="button" id="city" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled="disabled"><%= data.city %></div>
                <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                <ul class="dropdown-menu" aria-labelledby="city">
                    <li data-value="USA">USA</li>
                </ul>
            </div>-->
        </div>

        <div class="form-group">
            <label for="hobbies">Hobbies</label>
            <input name="hobbies" type="text" class="form-control" id="hobbies" placeholder="Hobbies" value="<%= data.hobbies %>">
        </div>
    </div>

    <div class="form-group-wrapper bio-wrapper clearfix">
        <div class="form-group">
            <label for="about">Bio</label>
            <textarea name="about" class="about" id="about" maxlength="2000"><%= data.about %></textarea>
        </div>
    </div>

    <div class="btn-control">
        <div class="cancel disable">
            <p>Reset</p>
        </div>
        <div class="save disable">
            <span>Update</span>
        </div>
    </div>
</script>
