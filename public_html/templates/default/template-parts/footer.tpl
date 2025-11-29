<!-- ========================= FOOTER ========================= -->
<footer class="section-footer border-top">
	<div class="container">
<section class="footer-top padding-y">
    <div class="row">

        {if $settingFooterWidgetOne != ""}
            <aside class="col">
                {$settingFooterWidgetOne}
            </aside>
        {/if}

        {if $settingFooterWidgetTwo != ""}
            <aside class="col">
                {$settingFooterWidgetTwo}
            </aside>
        {/if}

        {if $settingFooterWidgetThree != ""}
            <aside class="col">
                {$settingFooterWidgetThree}
            </aside>
        {/if}

        {if $settingFooterWidgetFour != ""}
            <aside class="col">
                {$settingFooterWidgetFour}
            </aside>
        {/if}

        {if $settingFooterWidgetFive != ""}
            <aside class="col">
                {$settingFooterWidgetFive}
            </aside>
        {/if}

    </div>
</section>

		<section class="footer-bottom border-top row">
			<div class="col-md-2">
				<p class="text-muted"> &copy {$smarty.now|date_format:"%Y"} {if $settingFooterWidgetSix != ""}{$settingFooterWidgetSix}{/if}</p>
			</div>
			<div class="col-md-8 text-md-center">
				{if $settingFooterWidgetSeven != ""}{$settingFooterWidgetSeven}{/if}
			</div>
			<div class="col-md-2 text-md-right text-muted">
				<!-- KORT LOGOER HER -->
			</div>
		</section>
	</div><!-- //container -->
</footer>
<!-- ========================= FOOTER END // ========================= -->


<!-- custom javascript -->
<script src="/templates/default/js/script.js" type="text/javascript"></script>

<script type="text/javascript">
/// some script

// jquery ready start
$(document).ready(function() {
	// jQuery code

});
// jquery end
</script>


{$settingFooterCode}


</body>
</html>
