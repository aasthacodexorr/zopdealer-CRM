<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave us a review - <?php echo $location['business_name']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Roboto', sans-serif;
            letter-spacing: 1px;
        }

        body {
            background-color: #f2f8f9;
        }

        .icons {
            cursor: pointer;
            transition: transform .2s;
        }

        .icons:hover {
            transform: scale(1.1);
        }

        .g-recaptcha>div {
            margin: auto;
        }

        .review-share-list li img {
            width: 200px;
            padding: 1em;
        }

        .review-share-list li {
            list-style-type: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" integrity="sha512-fRVSQp1g2M/EqDBL+UFSams+aw2qk12Pl/REApotuUVK1qEXERk3nrCFChouag/PdDsPk387HJuetJ1HBx8qAg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f2f8f9">
    <tbody>
        <tr>
            <td>
                <table style="background-color: #f2f8f9; max-width:670px; margin:0 auto;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                    <tbody>
                        <tr>
                            <td style="height:80px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="text-align:center;">
                                <img class="img-responsive" style="max-height: 75px;margin:auto;" src="<?php echo $business_logo; ?>" alt="Business Logo">
                            </td>
                        </tr>
                        <tr>
                            <td style="height:40px;">&nbsp;</td>
                        </tr>
                        <tr id="landing-feedback-page">
                            <td>
                                <table style="max-width:670px; background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12);-moz-box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12);box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12)" width="95%" cellspacing="0" cellpadding="0" border="0" align="center">
                                    <tbody>
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:0 15px;">
                                                <h1 style="border-bottom: 1px solid #777; color:#3075BA; font-weight:400; margin:0;font-size:20px;"><?php echo $location['business_name']; ?></h1>
                                                <p style="font-size:24px; color:#171f23de; margin:8px 0 0; line-height:48px;font-weight:700;">
                                                    How was your experience?
                                                </p>
                                                <p style="font-size:15px; color:#171f23de; margin:8px 0 0; line-height:24px;">
                                                    Hello <b><?php echo $first_name ?></b>,
                                                    We'd like to know how your experience has been. Your response will help us server you better in the future!
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:0 15px;">
                                                <i id="positive_feedback" title="I am happy with the service." class="icons fa-solid fa-face-smile fa-8x mr-5 text-success"></i>
                                                <i id="negative_feedback" title="I am not happy with the service." class="icons fa-solid fa-face-frown fa-8x text-danger"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr id="negative-feedback-form" style="display:none;">
                            <td>
                                <table style="max-width:670px; background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12);-moz-box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12);box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12)" width="95%" cellspacing="0" cellpadding="0" border="0" align="center">
                                    <tbody>
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:0 15px;">
                                                <h1 style="border-bottom: 1px solid #777; color:#3075BA; font-weight:400; margin:0;font-size:20px;"><?php echo $location['business_name']; ?></h1>
                                                <p style="font-size:15px; color:#171f23de; margin:8px 0 0; line-height:24px;">
                                                    We apologize your experience was not great. How can we improve?
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:0 15px;">
                                                <form id="form">
                                                    <br>
                                                    <div class="form-group">
                                                        <textarea class="form-control" name="comment" placeholder="Let us know what went wrong..." rows="3"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="g-recaptcha" data-sitekey="<?php echo getenv('google.recaptcha.public') ?>"></div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mb-2">Submit</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr id="thank-you-page" style="display:none;">
                            <td>
                                <table style="max-width:670px; background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12);-moz-box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12);box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12)" width="95%" cellspacing="0" cellpadding="0" border="0" align="center">
                                    <tbody>
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:0 15px;">
                                                <h1 style="border-bottom: 1px solid #777; color:#3075BA; font-weight:400; margin:0;font-size:20px;"><?php echo $location['business_name']; ?></h1>
                                                <p style="font-size:15px; color:#171f23de; margin:8px 0 0; line-height:24px;">
                                                    We have received your feedback.
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:20px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Thank you for your feedback. Your response will help us server you better in the future!</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                        <tr style="font-size:12px; color:#171f23de; margin:8px 0 0; line-height:24px;">
                                            <td>You can close this window now.</td>
                                        </tr>
                                        <tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr id="review-share-page" style="display:none;">
                            <td>
                                <table style="max-width:670px; background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12);-moz-box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12);box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12)" width="95%" cellspacing="0" cellpadding="0" border="0" align="center">
                                    <tbody>
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:0 15px;">
                                                <h1 style="border-bottom: 1px solid #777; color:#3075BA; font-weight:400; margin:0;font-size:20px;"><?php echo $location['business_name']; ?></h1>
                                                <p style="font-size:15px; color:#171f23de; margin:8px 0 0; line-height:24px;">
                                                    Thank you for choosing us!<br> We would love it if you could leave us a review on one of the following sites:
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:20px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <ul class="review-share-list">

                                                    <?php if (!empty($google_review_url)) { ?>
                                                        <li>
                                                            <a class="review-share-item"><img data-zd-review-url="<?php echo $google_review_url; ?>" class="img-thumbnail btn btn-light" src="https://zopsoftware-asset.b-cdn.net/dist/assets/images/pngkit_google-png_157818.png" alt="" srcset="">
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if (!empty($facebook_review_url)) { ?>
                                                        <li>
                                                            <a class="review-share-item"><img data-zd-review-url="<?php echo $facebook_review_url; ?>" class="img-thumbnail btn btn-light" src="https://zopsoftware-asset.b-cdn.net/dist/assets/images/pngkit_facebook_reviews_logo.png" alt="" srcset="">
                                                            </a>
                                                        </li>
                                                    <?php } ?>


                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:20px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="height:80px;">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

</html>
<script>
    function httpGetAsync(url, callback) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {

        }
        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);
    }

    function httpPostRequest(url, data, callback) {
        let xhr = new XMLHttpRequest();

        const toast = Swal.mixin({
            showConfirmButton: false,
            position: "bottom",
            toast: true,
            timerProgressBar: true,
            confirmButtonText: "",
            title: `Saving your response...`,
        });

        toast.fire();
        toast.showLoading();

        xhr.open("POST", url);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                const response = JSON.parse(xhr.responseText);
                console.log(response)

                if (xhr.status === 200) {
                    toast.hideLoading();
                    toast.update({
                        title: `${response.success}`,
                        icon: "success",
                    });

                    callback();

                    setTimeout(() => {
                        toast.close();
                    }, 2000);

                } else {
                    toast.hideLoading();
                    toast.update({
                        title: `${response.errors}`,
                        icon: "error",
                    });

                    setTimeout(() => {
                        toast.close();
                    }, 2000);
                }
            }
        };

        setTimeout(() => {
            xhr.send(data);
        }, 1000);
    }

    const feedback_page = document.getElementById("landing-feedback-page");

    const negative_feedback_form = document.getElementById("negative-feedback-form");

    const positive_feedback = document.getElementById("positive_feedback");

    const negative_feedback = document.getElementById("negative_feedback");

    const thankyou_page = document.getElementById("thank-you-page");

    const review_share_page = document.getElementById("review-share-page");

    for (const element of document.getElementsByClassName("review-share-item")) {
        element.addEventListener("click", (e) => {
            e.preventDefault();
            window.open(e.target.attributes['data-zd-review-url'].value, '_blank');
        });
    }


    positive_feedback.addEventListener("click", () => {
        try {
            feedback_page.style.display = "none";
            review_share_page.style.display = "block";
            httpGetAsync(`${window.location.origin}/api/website/reviews/feedback/positive/status/<?php echo $encrypted_id; ?>`);
        } catch (error) {

        }
    });

    negative_feedback.addEventListener("click", () => {
        try {
            feedback_page.style.display = "none";
            negative_feedback_form.style.display = "block";
            httpGetAsync(`${window.location.origin}/api/website/reviews/feedback/negative/status/<?php echo $encrypted_id; ?>`);
        } catch (error) {

        }
    });

    const form = document.getElementById("form");
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        httpPostRequest(`${window.location.origin}/api/website/reviews/feedback/update/<?php echo $encrypted_id; ?>`,
            new FormData(form), (e) => {
                feedback_page.style.display = "none";
                negative_feedback_form.style.display = "none";
                thankyou_page.style.display = "block";
            });
    });
</script>