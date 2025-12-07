<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Services - Elite Football Agency</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <link rel="icon" type="image/png" href="assets/img/sefa.png" />
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              navy: "#0A2647",
              lime: "#57C84D",
              softblue: "#144272",
              darkgray: "#1E1E1E",
            },
          },
        },
      };
      document.addEventListener("DOMContentLoaded", () => {
        AOS.init({ duration: 1000, once: true, easing: "ease-in-out" });
      });
    </script>
    <style>
      html {
        scroll-behavior: smooth;
      }
    </style>
  </head>
  <body class="bg-white text-darkgray font-sans">
   
      <!-- HEADER COMPONENT -->
    <?php include 'components/header.php'; ?>

  

    <!-- HERO -->
    <section
      class="h-72 bg-cover bg-center relative"
      style="
        background-image: url('assets/img/steve_caulker.webp');
      "
      data-aos="fade-in"
    >
      <div
        class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/70 flex flex-col justify-center items-center text-center px-4"
      >
        <h1
          class="text-4xl md:text-5xl font-bold text-white"
          data-aos="fade-down"
        >
          Our Services
        </h1>
      </div>
    </section>

    <!-- SERVICES CARDS -->
    <section class="py-16 px-8 md:px-20">
      <div class="max-w-6xl mx-auto">
        <div class="grid md:grid-cols-3 gap-8">
          <div
            class="bg-white shadow-lg rounded-lg p-6 text-center"
            data-aos="zoom-in"
          >
            <h3 class="text-xl font-semibold mb-3">Player Management</h3>
            <p>
              Comprehensive guidance to help players advance their careers and
              make informed decisions.
            </p>
          </div>
          <div
            class="bg-white shadow-lg rounded-lg p-6 text-center"
            data-aos="zoom-in"
            data-aos-delay="100"
          >
            <h3 class="text-xl font-semibold mb-3">Contract Negotiation</h3>
            <p>
              Negotiating contracts with top clubs to secure the best
              opportunities for our players.
            </p>
          </div>
          <div
            class="bg-white shadow-lg rounded-lg p-6 text-center"
            data-aos="zoom-in"
            data-aos-delay="200"
          >
            <h3 class="text-xl font-semibold mb-3">Scouting & Trials</h3>
            <p>
              Connecting players with clubs, tournaments, and professional
              trials worldwide.
            </p>
          </div>
          <div
            class="bg-white shadow-lg rounded-lg p-6 text-center"
            data-aos="zoom-in"
            data-aos-delay="300"
          >
            <h3 class="text-xl font-semibold mb-3">Career Consulting</h3>
            <p>
              Advice on career development, branding, sponsorships, and media
              presence.
            </p>
          </div>
          <div
            class="bg-white shadow-lg rounded-lg p-6 text-center"
            data-aos="zoom-in"
            data-aos-delay="400"
          >
            <h3 class="text-xl font-semibold mb-3">Legal Support</h3>
            <p>
              Ensuring contracts and agreements comply with regulations and
              protect our clients.
            </p>
          </div>
          <div
            class="bg-white shadow-lg rounded-lg p-6 text-center"
            data-aos="zoom-in"
            data-aos-delay="500"
          >
            <h3 class="text-xl font-semibold mb-3">Performance Analysis</h3>
            <p>
              Analyzing player stats, performance, and potential to guide
              professional decisions.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- FOOTER COMPONENTS-->
   
    <?php include 'components/footer.php'; ?>
  </body>
</html>
