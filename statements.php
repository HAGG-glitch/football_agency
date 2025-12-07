<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Statements - Sierra Elite Football Agency</title>
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

  

    <section class="py-16 px-8 md:px-20 max-w-4xl mx-auto" data-aos="fade-up">
      <h1 class="text-4xl font-bold mb-6">Statements & Policies</h1>
      <p class="mb-4">
        Sierra Elite Football Agency is committed to transparency and compliance with
        all applicable laws and regulations. Our statements provide guidance on
        our operations, client relationships, and ethical practices.
      </p>
      <p class="mb-4">
        We provide clear information regarding our services, contracts, and
        obligations to clients and partners. Any changes to policies are
        communicated promptly through our website and direct client
        communications.
      </p>
      <p class="mb-4">
        For legal inquiries or more detailed statements, please contact our
        office directly through the contact page.
      </p>
    </section>

    <!-- FOOTER COMPONENTS-->
   
    <?php include 'components/footer.php'; ?>
  </body>
</html>
