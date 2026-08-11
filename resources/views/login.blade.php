@extends('layouts.app')

<!-- PAGE TITLE
        ================================================== -->
        <section class="page-title-section">
            <div class="container">

                <div class="breadcrumbs-info">
                    <ul class="ps-0">
                        <li><a href="home-shop-1.html">Home</a></li>
                        <li><a href="#">Login</a></li>
                    </ul>
                </div>

            </div>
        </section>

<section class="md">            
                <div class="col-lg-6 mb-1-9 mb-lg-0 container">

                        <div class="common-block">

                            <div class="inner-title">
                                <h4 class="mb-0">Login</h4>
                                <p class="mb-0">Everything is simple with Login.</p>
                            </div>

                            <form method="post">

                                <div class="row">

                                    <div class="col-sm-12">

                                        <div class="form-group">
                                            <label>User Name</label>
                                            <input type="text" class="form-control" name="name" placeholder="Your user name here">
                                        </div>

                                    </div>

                                    <div class="col-sm-12">

                                        <div class="form-group">
                                            <label>Password </label>
                                            <input type="password" class="form-control" name="password" placeholder="Your password here">
                                        </div>

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="login-remember">
                                            <label class="form-check-label" for="login-remember">Keep me signed in</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-start text-md-end">
                                        <a href="account-password-recovery.html" class="m-link-muted">Forgot password?</a>
                                    </div>

                                </div>

                                <button type="button" class="butn-style2 mt-4">Login</button>

                            </form>

                        </div>

                    </div>
</section>