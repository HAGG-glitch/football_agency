<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sierra Elite Football Agency</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="assets/img/sefa.png" />

    <!-- AOS Library for scroll animations -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

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
    </script>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        AOS.init({
          duration: 1000,
          once: true,
          easing: "ease-in-out",
        });
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

    <!-- HERO SECTION -->
    <section
      class="h-screen bg-cover bg-center relative"
      style="background-image: url('assets/img/musa_tombo_hero.jpg');"
      data-aos="fade-up"
    >
      <div
        class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/70 flex flex-col justify-center items-center text-center px-4"
      >
        <h1
          data-aos="fade-down"
          data-aos-delay="200"
          class="text-5xl md:text-6xl font-bold text-white mb-4"
        >
          We Represent the Best
        </h1>
        <p
          data-aos="fade-up"
          data-aos-delay="400"
          class="text-xl md:text-2xl text-lime mb-6"
        >
          Building dreams, one player at a time
        </p>
        <a
          href="contactus.php"
          class="bg-lime hover:bg-softblue text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300"
          data-aos="zoom-in"
          data-aos-delay="600"
          >Get in Touch</a
        >
      </div>
    </section>

    <!-- ABOUT SECTION -->
    <section class="py-16 px-8 md:px-20 bg-gray-50" data-aos="fade-up">
      <div class="max-w-5xl mx-auto text-center">
        <h2
          class="text-4xl font-bold mb-6"
          data-aos="fade-down"
          data-aos-delay="200"
        >
          About Us
        </h2>
        <p
          class="text-lg text-darkgray mb-6"
          data-aos="fade-up"
          data-aos-delay="400"
        >
          Sierra Elite Football Agency is committed to representing top-tier talent and
          guiding players towards successful careers. Our team of experienced
          agents focuses on professional growth, contracts, and scouting.
        </p>
        <a
          href="aboutus.php"
          class="bg-navy hover:bg-softblue text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300"
          data-aos="zoom-in"
          data-aos-delay="600"
          >Learn More</a
        >
      </div>
    </section>

    <!-- SERVICES SECTION -->
    <section class="py-16 px-8 md:px-20" data-aos="fade-up">
      <div class="max-w-6xl mx-auto">
        <h2
          class="text-4xl font-bold text-center mb-12"
          data-aos="fade-down"
          data-aos-delay="200"
        >
          Our Services
        </h2>
        <div class="grid md:grid-cols-3 gap-8">
          <div
            class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center text-center"
          >
            <h3
              class="text-xl font-semibold mb-3"
              data-aos="fade-down"
              data-aos-delay="200"
            >
              Player Management
            </h3>
            <p data-aos="fade-up" data-aos-delay="400">
              Guiding your career from youth to professional leagues with
              personalized support.
            </p>
          </div>
          <div
            class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center text-center"
          >
            <h3
              class="text-xl font-semibold mb-3"
              data-aos="fade-down"
              data-aos-delay="200"
            >
              Contract Negotiation
            </h3>
            <p data-aos="fade-up" data-aos-delay="400">
              Ensuring the best terms and opportunities for our players with
              trusted legal support.
            </p>
          </div>
          <div
            class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center text-center"
          >
            <h3
              class="text-xl font-semibold mb-3"
              data-aos="fade-down"
              data-aos-delay="200"
            >
              Scouting & Trials
            </h3>
            <p data-aos="fade-up" data-aos-delay="400">
              Connecting players with clubs, tournaments, and trials for career
              advancement.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- FOOTER COMPONENTS-->
   
    <?php include 'components/footer.php'; ?>
  </body>
</html>
