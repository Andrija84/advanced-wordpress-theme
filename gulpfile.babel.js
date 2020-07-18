import { src, dest, watch, series, parallel } from 'gulp';
import yargs from 'yargs';
import sass from 'gulp-sass';
import cleancss from 'gulp-clean-css';
import gulpif from 'gulp-if';
import postcss from 'gulp-postcss';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'autoprefixer';
import imagemin from 'gulp-imagemin';
import del from 'del';
import browserSync from "browser-sync";
import wpPot from "gulp-wp-pot";
import info from "./package.json";
const minify = require('gulp-minify');
const concat = require('gulp-concat');
const merge = require('merge2')
const PRODUCTION = yargs.argv.prod;

/*
export const minifySCSS = () => {
  return src(['src/scss/*.scss'])
    .pipe(gulpif(!PRODUCTION, sourcemaps.init()))
    .pipe(sass().on('error', sass.logError))
    .pipe(gulpif(PRODUCTION, postcss([ autoprefixer ])))
    .pipe(gulpif(PRODUCTION, cleancss({compatibility:'ie8'})))
    .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
    .pipe(dest('dist/css'));
}

export const minifyCSS = () => {
  return src('src/css/*.css')
    .pipe(gulpif(!PRODUCTION, sourcemaps.init()))
    .pipe(gulpif(PRODUCTION, postcss([ autoprefixer ])))
    .pipe(gulpif(PRODUCTION, cleancss({compatibility:'ie8'})))
    .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
    .pipe(concat('bundle.css')) 
    .pipe(dest('dist/css'));
}
*/

export const optimizeImages = () => {
  return src('C:\/xampp\/htdocs\/gulp\/wp-content\/uploads/*.{jpg,jpeg,png,svg,gif}')
    .pipe(gulpif(PRODUCTION, imagemin()))
    .pipe(dest('C:\/xampp\/htdocs\/gulp\/wp-content\/uploads'));
}

export const minifyCSS = () => {
  return merge(
     src(['src/scss/*.scss'])
      .pipe(gulpif(!PRODUCTION, sourcemaps.init()))
      .pipe(sass().on('error', sass.logError))
      .pipe(gulpif(PRODUCTION, postcss([ autoprefixer ])))
      .pipe(gulpif(PRODUCTION, cleancss({compatibility:'ie8'})))
      .pipe(gulpif(!PRODUCTION, sourcemaps.write())),
    src('src/css/*.css')
      .pipe(gulpif(!PRODUCTION, sourcemaps.init()))
      .pipe(gulpif(PRODUCTION, postcss([ autoprefixer ])))
      .pipe(gulpif(PRODUCTION, cleancss({compatibility:'ie8'})))
      .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
  )
  .pipe(concat('bundle.css'))
  .pipe(dest('dist/css'))
  .pipe(server.stream());
}

export const minifyJS = () => {
  return src('src/js/*.js', { allowEmpty: true }) 
    //.pipe(gulpif(!PRODUCTION, sourcemaps.init()))
    .pipe(gulpif(PRODUCTION, minify({noSource: true})))
    //.pipe(gulpif(!PRODUCTION, sourcemaps.write()))
    .pipe(concat('bundle.js')) 
    .pipe(dest('dist/js'));
}



export const clean = () => {
  return del(['dist']);
}

export const pot = () => {
  return src("**/*.php")
  .pipe(
      wpPot({
        domain: "_themename",
        package: info.name
      })
    )
  .pipe(dest(`languages/${info.name}.pot`));
};


const server = browserSync.create();
export const serve = done => {
  server.init({
    proxy: "http://localhost/gulp" // put your local website link here
  });
  done();
};
export const reload = done => {
  server.reload();
  done();
};


export const watchForChanges = () => {
  //watch('src/scss/**/*.scss', minifySCSS);
  //watch('src/css/**/*.css', minifyCSS);
  //watch('src/scss/**/*.scss', series(minifyCSS, reload));
  watch('src/scss/**/*.scss', minifyCSS);
  watch('src/css/**/*.css', minifyCSS);
  watch('src/js/**/*.js', minifyJS);
  watch('C:\/xampp\/htdocs\/gulp\/wp-content\/uploads/*.{jpg,jpeg,png,svg,gif}', optimizeImages);
  watch("**/*.php", reload);
}


export const dev = series(clean, parallel(minifyCSS, minifyJS, optimizeImages), serve, watchForChanges);
//export const build = series(clean, minifySCSS, minifyCSS, optimizeImages);
export const build = series(clean, parallel(minifyCSS,minifyJS, optimizeImages), pot);
export default dev;

