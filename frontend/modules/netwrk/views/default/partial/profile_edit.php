<?php
    use yii\helpers\Url;
?>
<div class="modal modal-profile" id='modal_profile_edit'>
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
                    <div class="form-group-wrapper clearfix">
                        <div class="form-group">
                            <label for="firstName">First name</label>
                            <input type="text" class="form-control" id="firstName" placeholder="First name">
                        </div>

                        <div class="form-group">
                            <label for="lastName">Last name</label>
                            <input type="text" class="form-control" id="lastName" placeholder="Last name">
                        </div>
                    </div>

                    <div class="form-group-wrapper clearfix">
                        <div class="form-group">
                            <label for="userName">Username</label>
                            <input type="text" class="form-control" id="userName" placeholder="Username" disabled="disabled">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Email" disabled="disabled">
                        </div>
                    </div>

                    <div class="form-group-wrapper clearfix">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <div class="radio_gender">
                                <input type="radio" class="input_radio" name="gender" id="profileEditMale" value="Male"> <label for="profileEditMale"> Male </label>
                                <input type="radio" class="input_radio" name="gender" id="profileEditFemale" value="Female"><label for="profileEditFemale"> Female</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="homeZipCode">Home zip code</label>
                            <input type="text" class="form-control" id="homeZipCode" placeholder="Home zip code">
                        </div>
                    </div>

                    <div class="form-group-wrapper clearfix">
                        <div class="form-group">
                            <label for="dob">Date of birth</label>
                            <input type="text" class="form-control" id="dob" placeholder="Date of birth">
                        </div>

                        <div class="form-group">
                            <label for="maritalStatus">Marital status</label>
                            <div class="dropdown input-group">
                                <div class="dropdown-toggle" type="button" id="maritalStatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Marital status</div>
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
                            <input type="text" class="form-control" id="work" placeholder="Work">
                        </div>

                        <div class="form-group">
                            <label for="education">Education</label>
                            <input type="text" class="form-control" id="education" placeholder="Education">
                        </div>
                    </div>

                    <div class="form-group-wrapper clearfix">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <div class="dropdown input-group">
                                <div class="dropdown-toggle" type="button" id="country" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled="disabled">Country</div>
                                <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                                <ul class="dropdown-menu" aria-labelledby="maritalStatus">
                                    <li data-value="USA">USA</li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="state">State</label>
                            <div class="dropdown input-group">
                                <div class="dropdown-toggle" type="button" id="state" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">State</div>
                                <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                                <ul class="dropdown-menu" aria-labelledby="maritalStatus">
                                    <li data-value="Single">Indiana Police</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-wrapper clearfix">
                        <div class="form-group">
                            <label for="city">City</label>
                            <div class="dropdown input-group">
                                <div class="dropdown-toggle" type="button" id="city" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled="disabled">Country</div>
                                <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                                <ul class="dropdown-menu" aria-labelledby="maritalStatus">
                                    <li data-value="USA">USA</li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="hobbies">Hobbies</label>
                            <input type="text" class="form-control" id="hobbies" placeholder="Hobbies">
                        </div>
                    </div>

                    <div class="form-group-wrapper bio-wrapper clearfix">
                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea class="about" id="bio" maxlength="2000"></textarea>
                        </div>
                    </div>

                    <div class="btn-control">
                        <div class="cancel disable">
                            <p>Reset</p>
                        </div>
                        <div class="save disable">
                            <span>Save</span>
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
