<!-- ========================= FOOTER ========================= -->
<footer class="section-footer border-top">
	<div class="container">
		<section class="footer-top padding-y">
			<div class="row">
				<aside class="col-md col-6">
					{if isset($footerWidgetOne)}
						{$footerWidgetOne}
					{/if}
				</aside>
				<aside class="col-md col-6">
					{if isset($footerWidgetTwo)}
						{$footerWidgetTwo}
					{/if}
				</aside>
				<aside class="col-md col-6">
					{if isset($footerWidgetThree)}
						{$footerWidgetThree}
					{/if}
				</aside>
				<aside class="col-md col-6">
					{if isset($footerWidgetFour)}
						{$footerWidgetFour}
					{/if}
				</aside>
				<aside class="col-md">
					{if isset($footerWidgetFive)}
						{$footerWidgetFive}
					{/if}
				</aside>
			</div> <!-- row.// -->
		</section>	<!-- footer-top.// -->

		<section class="footer-bottom border-top row">
			<div class="col-md-2">
				<p class="text-muted"> &copy {$smarty.now|date_format:"%Y"}  {if isset($footerWidgetSix)}{$footerWidgetSix}{/if}</p>
			</div>
			<div class="col-md-8 text-md-center">
				{if isset($footerWidgetSeven)}{$footerWidgetSeven}{/if}
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

</body>
</html>
