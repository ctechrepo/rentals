</div><!--/.container-->

<footer class="footer">
    <div class="container">

        <div id="footerwrap">
            <div id="footer">

                <div class="segment">
                    <h2>Normal, Illinois</h2>
                    <p>1540 E. College Ave. Normal, IL 61761</p>
                    <p>Phone: (800) 322-5019 or (309) 452-7436</p>
                    <h4>Store Hours</h4>
                    <p>Monday - Thursday 10am - 7pm<br />
                        Friday 10am - 6pm<br />
                        Saturday 10am - 5pm</p>
                </div>

                <div class="segment">
                    <h2>Champaign, Illinois</h2>
                    <p>27 E. Marketview Dr. Champaign, IL 61820</p>
                    <p>Phone: (800) 842-0035 or (217) 356-8005</p>
                    <h4>Store Hours</h4>
                    <p>Monday - Thursday 10am - 7pm<br />
                        Friday 10am - 6pm<br />
                        Saturday 10am - 5pm</p>
                </div>

                <div class="copyrightarea">
                    <a href="./policy.php?policy=terms"> Terms & Conditions </a> <br/>
                    <a href="./policy.php?policy=pricing"> Pricing Policy </a> <br/>
                    <a href="./policy.php?policy=privacy"> Privacy Policy </a> <br/><br/><br/>
                    <p>2012 &copy; The Music Shoppe. All rights reserved.  <a href="http://www.ctechservices.com" target="_blank">Web design and hosting</a> by Ctech Services.</p>
                </div>
            </div>
            <br clear="all" />
        </div>
    </div>
</footer>

<?php echo theme_view('parts/modal_login'); ?>

<div id="debug"></div>
<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script>window.jQuery || document.write('')</script>

<!-- This would be a good place to use a CDN version of jQueryUI if needed -->
<?php echo Assets::js(); ?>

</body>
</html>