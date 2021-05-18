import {src, dest, watch, parallel, series} from 'gulp'
import babel from 'gulp-babel'
import cleanCSS from 'gulp-clean-css'
import concat from 'gulp-concat'
import del from 'del'
import download from 'gulp-download-files'
import plumber from 'gulp-plumber'
import sass from 'gulp-sass'
import sourcemaps from 'gulp-sourcemaps'
import vinylPaths from 'vinyl-paths'
import importCSS from "gulp-import-css"
import rename from "gulp-rename"



const sources = {
  css: [
    "./src/css/animations.scss",
    "./src/css/base.scss",
    "./src/css/layout.scss",
    "./src/css/module.scss",
    "./src/css/state.scss",
    "./src/css/theme.scss",
  ],
  jsHead: [
    "./src/js/head/*"
  ],
  jsFooter: [
    "./src/js/footer/*"
  ]
}

const dirs = {
  dest: "./dist",
  destCSS: "./src/css"
}

export const clean = () => src("./dist/*")
  .pipe(vinylPaths(del))

export const buildStylesProd = () => src(sources.css)
  .pipe(plumber())
    .pipe(sass())
    .pipe(concat('core.min.css'))
    .pipe(cleanCSS({compability: 'ie8'}))
  .pipe(plumber.stop())
  .pipe(dest(dirs.dest))

  export const buildStylesDev = () => src( sources.css )
  .pipe(plumber())
  .pipe(sourcemaps.init())
    .pipe(sass())
    .pipe(concat('core.dev.css'))
  .pipe(sourcemaps.write('.'))
  .pipe(plumber.stop())
  .pipe(dest(dirs.dest))

  export const buildJSHead = () => src( sources.jsHead )
    .pipe( concat( "scripts-head.js" ) )
    .pipe( babel({
      presets: ['@babel/env']
    }) )
    .pipe( dest( dirs.dest ) )

  export const buildJSFooter = () => src( sources.jsFooter )
    .pipe( concat( "scripts-footer.js" ) )
    .pipe( babel({
      presets: ['@babel/env']
    }) )
    .pipe( dest( dirs.dest ) )


export const dev = series(clean, parallel(buildStylesDev, buildStylesProd, buildJSFooter, buildJSHead));

export default dev;