<template>
  <main class="home-page">
    <h1 class="visually-hidden">Heroes examples</h1>

    <section id="top" class="hero centered-hero">
      <div class="brand-mark" aria-hidden="true">B</div>
      <p class="eyebrow">Bootstrap-inspired layouts</p>
      <h2>Centered hero</h2>
      <p class="hero-copy">
        Quickly design and customize responsive mobile-first sites with a clean,
        flexible layout system and reusable components for every screen size.
      </p>
      <div class="actions">
        <a class="button primary" href="#signup">Primary button</a>
        <a class="button secondary" href="#features">Secondary</a>
      </div>
    </section>

    <div class="divider"></div>

    <section id="features" class="hero screenshot-hero">
      <div class="content-column">
        <p class="eyebrow">A clearer starting point</p>
        <h2>Centered screenshot</h2>
        <p class="hero-copy">
          Build polished interfaces with simple sections, readable spacing, and
          a visual system that keeps the important actions easy to find.
        </p>
        <div class="actions">
          <a class="button primary" href="#signup">Primary button</a>
          <a class="button secondary" href="#split-layout">Explore layout</a>
        </div>
      </div>
      <div class="browser-frame" aria-label="Example dashboard preview">
        <div class="browser-bar"><span></span><span></span><span></span></div>
        <div class="browser-body">
          <aside class="preview-sidebar"><strong>studio</strong><i></i><i></i><i></i></aside>
          <div class="preview-main"><div class="preview-line wide"></div><div class="preview-line"></div><div class="preview-grid"><i></i><i></i><i></i></div></div>
        </div>
      </div>
    </section>

    <div class="divider"></div>

    <section id="split-layout" class="split-section">
      <div class="visual-panel" aria-hidden="true">
        <div class="floating-card"><span class="dot"></span><b>07</b><small>new projects</small></div>
        <div class="chart"><i></i><i></i><i></i><i></i><i></i></div>
      </div>
      <div class="content-column">
        <p class="eyebrow">Flexible by design</p>
        <h2>Responsive left-aligned hero with image</h2>
        <p class="hero-copy">
          Give your content room to breathe while keeping the next action close.
          This pattern works well for dashboards, product introductions, and team pages.
        </p>
        <div class="actions">
          <a class="button primary" href="#signup">Primary</a>
          <a class="button secondary" href="#dark-hero">Default</a>
        </div>
      </div>
    </section>

    <div class="divider"></div>

    <section id="signup" class="signup-section">
      <div class="signup-copy">
        <p class="eyebrow">Start something useful</p>
        <h2>Vertically centered hero sign-up form</h2>
        <p class="hero-copy">
          Create a workspace for your team and keep your customer and employee
          information organized from one friendly home page.
        </p>
      </div>
      <form class="signup-form" @submit.prevent="submitForm">
        <label for="email">Email address</label>
        <input id="email" v-model="email" type="email" placeholder="name@example.com" required />
        <label for="password">Password</label>
        <input id="password" v-model="password" type="password" placeholder="Password" required />
        <label class="check-label"><input v-model="remember" type="checkbox" /> Remember me</label>
        <button class="button primary full-width" type="submit">Sign up</button>
        <small v-if="submitted" class="success-message">Thanks! Your workspace is ready to begin.</small>
        <small v-else>By clicking Sign up, you agree to the terms of use.</small>
      </form>
    </section>

    <div class="divider"></div>

    <section class="border-hero">
      <div class="border-copy">
        <p class="eyebrow">One place for your work</p>
        <h2>Border hero with cropped image and shadows</h2>
        <p class="hero-copy">A compact, confident section for presenting a service, a new feature, or a clear next step.</p>
        <div class="actions"><a class="button primary" href="#signup">Primary</a><a class="button secondary" href="#features">Default</a></div>
      </div>
      <div class="cropped-preview"><div class="preview-glow"></div><div class="preview-window"><strong>Overview</strong><span></span><span></span><span></span></div></div>
    </section>

    <section id="dark-hero" class="dark-hero">
      <p class="eyebrow">A focused finish</p>
      <h2>Dark color hero</h2>
      <p class="hero-copy">Keep the final call to action distinct with a deep contrasting section and accessible controls.</p>
      <div class="actions"><a class="button dark-primary" href="#signup">Custom button</a><a class="button dark-secondary" href="#top">Secondary</a></div>
    </section>
  </main>
</template>

<script>
export default {
  name: 'HomeView',
  data() {
    return { email: '', password: '', remember: false, submitted: false };
  },
  methods: {
    submitForm() {
      this.submitted = true;
    }
  }
};
</script>

<style scoped>
:global(*) { box-sizing: border-box; }
.home-page { --ink: #17212b; --muted: #607080; --blue: #2563eb; --line: #dbe3ea; color: var(--ink); background: #f8fafc; }
.visually-hidden { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }
.hero, .split-section, .signup-section, .border-hero { max-width: 1120px; margin: 0 auto; padding: 88px 32px; }
.centered-hero { text-align: center; padding-top: 110px; }
.brand-mark { display: grid; place-items: center; width: 72px; height: 72px; margin: 0 auto 24px; border-radius: 18px; background: #f0b429; color: #17212b; font-size: 42px; font-weight: 900; transform: rotate(-5deg); box-shadow: 8px 8px 0 #17212b; }
.eyebrow { margin: 0 0 14px; color: #b45309; font-size: .76rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
h2 { margin: 0 0 18px; font-size: clamp(2.25rem, 5vw, 4.6rem); line-height: 1.02; letter-spacing: -.04em; }
.hero-copy { max-width: 650px; margin: 0 auto 28px; color: var(--muted); font-size: 1.14rem; line-height: 1.7; }
.actions { display: flex; justify-content: center; flex-wrap: wrap; gap: 12px; }
.button { display: inline-flex; align-items: center; justify-content: center; min-height: 48px; padding: 0 22px; border: 1px solid transparent; border-radius: 8px; font-weight: 750; text-decoration: none; cursor: pointer; transition: transform .2s ease, box-shadow .2s ease, background .2s ease; }
.button:hover { transform: translateY(-2px); box-shadow: 0 8px 18px #17212b22; }
.primary { background: var(--blue); color: #fff; }.secondary { border-color: #9aa8b5; color: var(--ink); background: #fff; }
.divider { height: 1px; background: var(--line); }.screenshot-hero { text-align: center; }.content-column { max-width: 720px; margin: 0 auto; }.browser-frame { max-width: 820px; margin: 46px auto 0; overflow: hidden; border: 1px solid #c7d2dc; border-radius: 12px 12px 0 0; background: #fff; box-shadow: 0 20px 55px #2135471c; }.browser-bar { display: flex; gap: 7px; padding: 14px 18px; background: #e8eef3; }.browser-bar span { width: 9px; height: 9px; border-radius: 50%; background: #9aa8b5; }.browser-body { display: flex; min-height: 230px; }.preview-sidebar { width: 24%; padding: 25px 18px; background: #17212b; color: #fff; text-align: left; }.preview-sidebar i, .preview-line, .preview-grid i, .preview-window span { display: block; height: 10px; margin-top: 20px; border-radius: 4px; background: #cbd5df; }.preview-sidebar i { background: #607080; }.preview-main { flex: 1; padding: 28px; text-align: left; }.preview-line { width: 44%; margin: 0 0 12px; }.preview-line.wide { width: 68%; height: 18px; background: #17212b; }.preview-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-top: 30px; }.preview-grid i { height: 100px; margin: 0; background: #dce8ff; }
.split-section { display: grid; grid-template-columns: 1fr 1fr; gap: 70px; align-items: center; }.split-section .content-column { margin: 0; }.split-section .hero-copy { margin-left: 0; }.visual-panel { position: relative; min-height: 380px; overflow: hidden; border-radius: 18px; background: #f0b429; }.chart { position: absolute; right: 12%; bottom: 14%; left: 12%; display: flex; align-items: end; gap: 14px; height: 170px; }.chart i { flex: 1; border-radius: 8px 8px 0 0; background: #17212b; }.chart i:nth-child(1) { height: 38%; }.chart i:nth-child(2) { height: 62%; }.chart i:nth-child(3) { height: 48%; }.chart i:nth-child(4) { height: 82%; }.chart i:nth-child(5) { height: 70%; }.floating-card { position: absolute; top: 52px; left: 48px; display: grid; grid-template-columns: 18px 1fr; gap: 2px 10px; padding: 18px; border-radius: 12px; background: #fff; box-shadow: 0 16px 25px #17212b33; }.floating-card .dot { grid-row: span 2; width: 14px; height: 14px; border-radius: 50%; background: #e05252; }.floating-card b { font-size: 1.6rem; }.floating-card small { color: var(--muted); }
.signup-section { display: grid; grid-template-columns: 1.15fr .85fr; gap: 80px; align-items: center; }.signup-copy .hero-copy { margin-left: 0; }.signup-form { padding: 30px; border: 1px solid var(--line); border-radius: 12px; background: #fff; box-shadow: 0 16px 35px #21354710; }.signup-form label { display: block; margin-bottom: 8px; font-size: .9rem; font-weight: 700; }.signup-form input[type=email], .signup-form input[type=password] { width: 100%; margin-bottom: 18px; padding: 13px 14px; border: 1px solid #bcc8d3; border-radius: 7px; font: inherit; }.check-label { display: flex !important; align-items: center; gap: 8px; margin: 2px 0 20px; font-weight: 500 !important; }.check-label input { width: 16px; height: 16px; }.full-width { width: 100%; }.signup-form small { display: block; margin-top: 18px; color: var(--muted); line-height: 1.5; }.success-message { color: #16734a !important; font-weight: 700; }
.border-hero { display: grid; grid-template-columns: 1.1fr .9fr; align-items: center; gap: 34px; margin-top: 72px; border: 1px solid var(--line); border-radius: 16px; background: #fff; box-shadow: 0 18px 40px #21354712; }.border-copy .hero-copy { margin-left: 0; }.cropped-preview { position: relative; min-height: 320px; overflow: hidden; border-radius: 14px; background: #17212b; }.preview-glow { position: absolute; top: 30px; right: -30px; width: 230px; height: 230px; border-radius: 50%; background: #f0b429; }.preview-window { position: absolute; top: 70px; left: 35px; width: 78%; min-height: 190px; padding: 22px; border-radius: 10px; background: #f8fafc; box-shadow: 0 15px 30px #0004; }.preview-window strong { display: block; font-size: 1.2rem; }.preview-window span { width: 74%; margin-top: 22px; background: #b9c7d4; }.preview-window span:nth-of-type(2) { width: 56%; }.preview-window span:nth-of-type(3) { width: 88%; }
.dark-hero { margin-top: 88px; padding: 96px 24px; color: #fff; text-align: center; background: #17212b; }.dark-hero .eyebrow { color: #f0b429; }.dark-hero .hero-copy { color: #cbd5df; }.dark-primary { background: #f0b429; color: #17212b; }.dark-secondary { border-color: #9aa8b5; color: #fff; background: transparent; }
@media (max-width: 760px) { .hero, .split-section, .signup-section, .border-hero { padding: 64px 20px; }.split-section, .signup-section, .border-hero { grid-template-columns: 1fr; gap: 38px; }.visual-panel { min-height: 300px; }.browser-body { min-height: 180px; }.preview-sidebar { width: 28%; }.signup-section { margin: 0; }.border-hero { margin: 40px 16px 0; }.dark-hero { margin-top: 40px; } h2 { font-size: 2.6rem; } }
@media (max-width: 420px) { .actions { flex-direction: column; }.actions .button { width: 100%; }.browser-frame { margin-top: 30px; }.preview-grid { gap: 7px; }.preview-grid i { height: 70px; }.floating-card { left: 22px; }.cropped-preview { min-height: 270px; } }
</style>
