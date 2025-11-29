{include file='template-parts/head.tpl'}

<div class="row mt-5">
<div class="col-md-4"></div>

	<div class="col-md-4">

<!-- ============================ COMPONENT LOGIN 3  ================================= -->
	<div class="card">
      <div class="card-body">
      <h4 class="card-title mb-4">Sign in</h4>
      <form method="post" action="/admin/wcio_login.php">
          <div class="form-group">
            <div class="input-group">
				<div class="input-group-prepend">
				    <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
				 </div>
				<input name="adminEmail" class="form-control" placeholder="Email" type="text">
			</div> <!-- input-group.// -->
          </div> <!-- form-group// -->
          <div class="form-group">
            <div class="input-group">
				<div class="input-group-prepend">
				    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
				 </div>
				<input name="adminPassword" class="form-control" placeholder="Password" type="password" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACIUlEQVQ4EX2TOYhTURSG87IMihDsjGghBhFBmHFDHLWwSqcikk4RRKJgk0KL7C8bMpWpZtIqNkEUl1ZCgs0wOo0SxiLMDApWlgOPrH7/5b2QkYwX7jvn/uc//zl3edZ4PPbNGvF4fC4ajR5VrNvt/mo0Gr1ZPOtfgWw2e9Lv9+chX7cs64CS4Oxg3o9GI7tUKv0Q5o1dAiTfCgQCLwnOkfQOu+oSLyJ2A783HA7vIPLGxX0TgVwud4HKn0nc7Pf7N6vV6oZHkkX8FPG3uMfgXC0Wi2vCg/poUKGGcagQI3k7k8mcp5slcGswGDwpl8tfwGJg3xB6Dvey8vz6oH4C3iXcFYjbwiDeo1KafafkC3NjK7iL5ESFGQEUF7Sg+ifZdDp9GnMF/KGmfBdT2HCwZ7TwtrBPC7rQaav6Iv48rqZwg+F+p8hOMBj0IbxfMdMBrW5pAVGV/ztINByENkU0t5BIJEKRSOQ3Aj+Z57iFs1R5NK3EQS6HQqF1zmQdzpFWq3W42WwOTAf1er1PF2USFlC+qxMvFAr3HcexWX+QX6lUvsKpkTyPSEXJkw6MQ4S38Ljdbi8rmM/nY+CvgNcQqdH6U/xrYK9t244jZv6ByUOSiDdIfgBZ12U6dHEHu9TpdIr8F0OP692CtzaW/a6y3y0Wx5kbFHvGuXzkgf0xhKnPzA4UTyaTB8Ph8AvcHi3fnsrZ7Wore02YViqVOrRXXPhfqP8j6MYlawoAAAAASUVORK5CYII=&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
			</div> <!-- input-group.// -->
          </div> <!-- form-group// -->

          <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block"> Login  </button>
          </div> <!-- form-group// -->

      </form>
      </div> <!-- card-body.// -->
    </div> <!-- card .// -->
<!-- ============================ COMPONENT LOGIN 3 END.// ================================= -->

	</div>

<div class="col-md-4"></div>

</div>

{include file='template-parts/footer.tpl'}
