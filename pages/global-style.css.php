html {
  width: 100%;
  height: 100%;
}

body {
  width: 100%;
  height: 100%;
  display: grid;
  --slope: 100px;
  grid-template-columns: 1fr var(--slope) 1fr;
  grid-template-rows: 1fr;
  margin: 0;
}

@media (orientation: portrait) {
  body {
    --slope: 50px;
    grid-template-columns: 1fr;
    grid-template-rows: 1fr var(--slope) 1fr;
  }
}

.background {
  grid-row: 1 / -1;
  grid-column: 1 / 3;
  background: rgb(0, 0, 0, .5);
  clip-path: polygon(0 0, calc(100% - 5px) 0, calc(100% - var(--slope) + 5px) 100%, 0 100%);
}

@media (orientation: portrait) {
  .background {
    grid-row: 1 / 3;
    grid-column: 1 / -1;
    clip-path: polygon(0 0, 100% 0, 100% calc(100% - var(--slope) + 5px), 0 calc(100% - 5px));
  }
}

header {
  grid-row: 1 / -1;
  grid-column: 1;
  z-index: 2;
  display: grid;
  justify-items: end;
  align-items: center;
}

@media (orientation: portrait) {
  header {
    grid-row: 1;
    grid-column: 1 / -1;
    justify-items: center;
  }
}

main {
  grid-row: 1 / -1;
  grid-column: 3;
}

@media (orientation: portrait) {
  main {
    grid-row: 3;
    grid-column: 1 / -1;
  }
}

#bio,
#projets,
#blog {
  display: none;
}

body[data-start=bio] #bio,
body[data-start=portfolio] #projets,
body[data-start=blog] #blog {
  display: grid;
}