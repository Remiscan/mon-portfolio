<html>
  <head>
    <style>
      html {
        width: 100%;
        height: 100%;
      }

      body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        scrollbar-gutter: stable;
        --oblique: 2rem;
        display: grid;
        grid-template-columns:
          [bg-left-start] 1fr
          [header-start] auto
          [header-end content-start] var(--oblique)
          [bg-left-end] 70ch
          [bg-right-start] var(--oblique)
          [content-end] 1fr
          [bg-right-end]
        ;
      }

      body > * {
        grid-row: 1;
      }

      .background {
        background-color: slateblue;
        z-index: 2;
      }

      .bg-left {
        grid-column: bg-left-start / bg-left-end;
        clip-path: polygon(0 0, calc(100% - var(--oblique)) 0, 100% 100%, 0 100%);
      }

      .bg-right {
        grid-column: bg-right-start / bg-right-end;
        clip-path: polygon(0 0, 100% 0, 100% 100%, var(--oblique) 100%);
      }

      .content {
        grid-column: bg-left-start / bg-right-end;
        overflow-x: hidden;
        overflow-y: auto;
        scrollbar-gutter: stable;
        display: grid;
        grid-template-columns: 1fr [main] calc(70ch + 2 * var(--oblique)) 1fr;
      }

      main {
        grid-column: main;
        padding-right: calc(2 * var(--oblique));
        display: flex;
        flex-direction: column;
        gap: 20px;
      }

      main > div {
        background-color: red;
        height: 300px;
      }
    </style>
  </head>

  <body>
    <div class="background bg-left"></div>
    <div class="content">
      <main>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </main>
    </div>
    <div class="background bg-right"></div>
  </body>
</html>