<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us - Elite Football Agency</title>
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
        background-image: url('assets/img/group.jpg');
      "
      data-aos="fade-in"
    >
      <div
        class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center px-4"
      >
        <h1
          class="text-4xl md:text-5xl font-bold text-white"
          data-aos="fade-down"
        >
          Contact Us
        </h1>
      </div>
    </section>

    <!-- CONTACT FORM + INFO -->
    <section class="py-16 px-8 md:px-20">
      <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12">
        <!-- FORM -->
        <div data-aos="fade-right">
          <h2 class="text-3xl font-bold mb-6">Get in Touch</h2>
          <form class="space-y-4">
            <input
              type="text"
              placeholder="Full Name"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-lime"
            />
            <input
              type="email"
              placeholder="Email"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-lime"
            />
            <textarea
              placeholder="Message"
              rows="5"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-lime"
            ></textarea>
            <button
              type="submit"
              class="bg-lime hover:bg-softblue text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300"
            >
              Send Message
            </button>
          </form>
        </div>
        <!-- INFO -->
        <div class="space-y-6" data-aos="fade-left">
          <h2 class="text-3xl font-bold mb-6">Our Office</h2>
          <p><strong>Address:</strong> 123 Football Avenue, Sierra Leone</p>
          <p><strong>Email:</strong> info@elitefootball.com</p>
          <p><strong>Phone:</strong> +232 123 456 789</p>
          <div class="flex space-x-4 mt-4">
            <a href="#" class="hover:text-lime transition-colors duration-300"
              >Facebook</a
            >
            <a href="#" class="hover:text-lime transition-colors duration-300"
              >Twitter</a
            >
            <a href="#" class="hover:text-lime transition-colors duration-300"
              >Instagram</a
            >
          </div>
        </div>
      </div>
    </section>

     <!-- FOOTER COMPONENTS-->
   
    <?php include 'components/footer.php'; ?>
  </body>
</html>
