<template>
  <section class="centered-hero" aria-labelledby="home-hero-title">
    <svg class="hero-logo" viewBox="0 0 72 57" role="img" aria-label="Bootstrap">
      <path fill="#7910ff" d="M15 0h42c7 0 7 7 8 16 0 5 3 9 7 12-4 3-7 7-7 12-1 10-1 17-8 17H15c-7 0-7-7-8-17 0-5-3-9-7-12 4-3 7-7 7-12C8 7 8 0 15 0Z" />
      <text x="36" y="43" fill="#fff" font-family="Arial, sans-serif" font-size="43" text-anchor="middle">B</text>
    </svg>
    <h1 id="home-hero-title">Centered hero</h1>
    <p>
      Quickly design and customize responsive mobile-first sites with Bootstrap,
      the world’s most popular front-end open source toolkit, featuring Sass
      variables and mixins, responsive grid system, extensive prebuilt components,
      and powerful JavaScript plugins.
    </p>
    <div class="hero-actions">
      <a class="hero-button hero-primary" href="#home-signup">Primary button</a>
      <a class="hero-button hero-secondary" href="#columns">Secondary</a>
    </div>
  </section>
</template>

<style scoped>
.centered-hero { padding: 96px 24px; text-align: center; }
.centered-hero * { box-sizing: border-box; }
.hero-logo { display: block; width: 72px; height: 57px; margin: 0 auto 24px; }
h1 { margin: 0 0 8px; color: #000; font-size: 48px; font-weight: 700; line-height: 1.2; }
p { max-width: 960px; margin: 0 auto 24px; color: #212529; font-size: 20px; font-weight: 300; line-height: 1.5; }
.hero-actions { display: flex; justify-content: center; flex-wrap: wrap; gap: 8px; }
.hero-button { display: inline-block; padding: 8px 24px; border: 1px solid; border-radius: 8px; font-size: 20px; line-height: 1.5; text-decoration: none; }
.hero-primary { border-color: #0d6efd; background: #0d6efd; color: #fff; }
.hero-primary:hover { border-color: #0a58ca; background: #0b5ed7; }
.hero-secondary { border-color: #6c757d; background: #fff; color: #6c757d; }
.hero-secondary:hover { background: #6c757d; color: #fff; }
.hero-button:focus-visible { outline: 3px solid #86b7fe; outline-offset: 4px; }
@media (max-width: 575px) {
  .centered-hero { padding: 56px 24px; }
  h1 { font-size: 36px; }
  .hero-actions { flex-direction: column; max-width: 320px; margin: auto; }
}
</style>
