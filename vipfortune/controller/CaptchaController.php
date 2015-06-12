<?php
class CaptchaController extends Controller {
    function indexAction() {
        $captcha = new CaptchaValide(60, 22);
        $captcha->createImage(4);
    }
}
