<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Accessibility - Sierra Elite Football Agency</title>
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
      <h1 class="text-4xl font-bold mb-6">Accessibility Statement</h1>
      <p class="mb-4">
        Sierra Elite Football Agency is committed to ensuring digital accessibility for
        all users, including those with disabilities. We strive to provide a
        website experience that is usable, navigable, and inclusive for
        everyone.
      </p>
      <p class="mb-4">
        If you encounter any barriers while using our website, please contact us
        using our contact page. We continually review and update accessibility
        features to improve your experience.
      </p>
      <p class="mb-4">
        We follow best practices for web accessibility standards, including
        proper use of semantic HTML, alt text for images, and color contrast
        considerations.
      </p>
    </section>

    <!-- FOOTER COMPONENTS-->
   
    <?php include 'components/footer.php'; ?>
  </body>
</html>
