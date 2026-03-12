<section class="section_contact">
	<div class="container">
		<div class="row">
			<div class="col-lg-5">
				<div class="general_info">
					<div class="section-title mb-40">
						<h3 class="mb-15">Contáctenos</h3>
						<p>Estamos para escucharlo y resolver todas sus dudas.</p>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-6">
							<div class="single-item">
								<div class="icon">
									<i class="lni lni-phone"></i>
								</div>
								<div class="text">
									<p>+51 1 123 1234</p>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-6">
							<div class="single-item">
								<div class="icon">
									<i class="lni lni-whatsapp"></i>
								</div>
								<div class="text">
									<p>+51 999 999 999</p>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-6">
							<div class="single-item">
								<div class="icon">
									<i class="lni lni-envelope"></i>
								</div>
								<div class="text">
									<p>vatech.peru@vatechglobal.com</p>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-6">
							<div class="single-item">
								<div class="icon">
									<i class="lni lni-map-marker"></i>
								</div>
								<div class="text">
									<p>Av. Republica de panama 4077, Surquillo, Peru</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="contact-form-wrapper">
					<form action="<?= base_url() ?>contact/send_mail" method="POST">
						<div class="row">
							<div class="col-md-6">
								<div class="single-input">
									<label for="name">Nombre</label>
									<input type="text" id="name" name="name" class="form-input" placeholder="Nombre">
									<i class="lni lni-user"></i>
								</div>
							</div>
							<div class="col-md-6">
								<div class="single-input">
									<label for="email">Correo Electrónico</label>
									<input type="email" id="email" name="email" class="form-input" placeholder="Correo Electrónico">
									<i class="lni lni-envelope"></i>
								</div>
							</div>
							<div class="col-md-6">
								<div class="single-input">
									<label for="number">Celular</label>
									<input type="text" id="number" name="number" class="form-input" placeholder="Celular">
									<i class="lni lni-phone"></i>
								</div>
							</div>
							<div class="col-md-6">
								<div class="single-input">
									<label for="subject">Asunto</label>
									<input type="text" id="subject" name="subject" class="form-input" placeholder="Asunto">
									<i class="lni lni-text-format"></i>
								</div>
							</div>
							<div class="col-md-12">
								<div class="single-input">
									<label for="message">Mensaje</label>
									<textarea name="message" id="message" class="form-input" placeholder="Mensaje" rows="6"></textarea>
									<i class="lni lni-comments-alt"></i>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-button">
									<button type="submit" class="button radius-10">Enviar <i class="lni lni-telegram-original"></i> </button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
   