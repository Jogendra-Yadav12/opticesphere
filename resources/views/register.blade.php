@extends('layouts.app')

<!-- PAGE TITLE
        ================================================== -->
        <section class="page-title-section">
            <div class="container">

                <div class="breadcrumbs-info">
                    <ul class="ps-0">
                        <li><a href="home-shop-1.html">Home</a></li>
                        <li><a href="#">Register</a></li>
                    </ul>
                </div>

            </div>
        </section>

<section class="md">

                    <div class="col-lg-8 container">

                        <div class="common-block">

                            <div class="inner-title">
                                <h4 class="mb-0">Register</h4>
                                <p class="mb-0">There's lots of fun in Register.</p>
                            </div>

                            <form method="post">

                                <div class="row">

                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label>Your Name</label>
                                            <input type="text" class="form-control" name="name" placeholder="Your name here">
                                        </div>

                                    </div>

                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label>Your User Name</label>
                                            <input type="text" class="form-control" name="username" placeholder="Your user name here">
                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control" name="email" placeholder="Your email here">
                                        </div>

                                    </div>

                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label>Contact Number</label>
                                            <input type="text" class="form-control" name="phone" placeholder="+40-123 456 789">
                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" name="password" placeholder="Your password here">
                                        </div>

                                    </div>

                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label>Re-Password</label>
                                            <input type="password" class="form-control" name="re-password" placeholder="Your re-password here">
                                        </div>

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-12 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="terms-condition">
                                            <label class="form-check-label" for="terms-condition">I agree to the <a href="#" class="text-primary">Terms & Conditions</a></label>
                                        </div>
                                    </div>

                                </div>

                                <button type="button" class="butn-style2 mt-4">Register</button>

                            </form>

                        </div>
                    </div>
        </section>