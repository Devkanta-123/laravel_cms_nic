<template>
    <div class="row align-items-center">
        <div class="col-lg-5">
            <div class="contact__content">
                <div class="section-title mb-35">
                    <h2 class="title contactus">How can we help you?</h2>
                </div>

            </div>
        </div>
        <div class="col-lg-7">
            <div class="contact__form-wrap">
                <h2 class="title contactus">Give Us a Message</h2>
                <p>Your email address will not be published. Required fields are marked *</p>
                <form>
                    <div class="form-grp">
                        <textarea name="message" placeholder="Message"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-grp">
                                <input type="text" name="name" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-grp">
                                <input type="email" name="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-grp">
                                <input type="number" name="phone" placeholder="Phone">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn">Submit post</button>
                </form>
            </div>
        </div>
    </div>
</template>