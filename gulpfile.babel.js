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
//const imageminMozjpeg = require('imagemin-mozjpeg');
const onlyChanged = require('gulp-changed')
const minify = require('gulp-minify');
const concat = require('gulp-concat');
const merge = require('merge2')
const PRODUCTION = yargs.argv.prod;

//const mediaLibrarySrc = 'C:\/xampp\/htdocs\/gulp\/wp-content\/uploads/*.{jpg,jpeg,png,svg,gif}';
//const mediaLibraryDest = 'C:\/xampp\/htdocs\/gulp\/wp-content\/uploads/';



export const optimizeMediaLibrary = () => {
  return src('C:\/xampp\/htdocs\/gulp\/wp-content\/uploads/*.{jpg,jpeg,png,svg,gif}')
  //.pipe(newer(mediaLibraryDest))
  //.pipe(onlyChanged('C:\/xampp\/htdocs\/gulp\/wp-content\/uploads/*.{jpg,jpeg,png,svg,gif}', {hasChanged: onlyChanged.compareContents}))
  .pipe(gulpif(PRODUCTION, imagemin()))
  .pipe(dest('C:\/xampp\/htdocs\/gulp\/wp-content\/uploads/'));
}




export const optimizeStaticImages = () => {
  return src(['images/*.{jpg,jpeg,png,svg,gif}'])
  //.pipe(newer(mediaLibraryDest))
  .pipe(gulpif(PRODUCTION, imagemin()))
  .pipe(dest('images/'));
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
    .pipe(gulpif(!PRODUCTION, sourcemaps.init()))
    .pipe(gulpif(PRODUCTION, minify({noSource: true})))
    .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
    .pipe(concat('bundle.js')) 
    .pipe(dest('dist/js'));
}

export const clean = () => {
  return del(['dist']);
}

export const copyImagesTemp = () => {
  return src('imageTemp/*.{jpg,jpeg,png,svg,gif}', { allowEmpty: true, overwrite: true })
  .pipe(dest('C:\/xampp\/htdocs\/gulp\/wp-content\/uploads'));
}



export const cleanImagesTemp = () => {
  return del(['imageTemp']);
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



export const watchChanges = () => {
  watch('src/scss/**/*.scss', minifyCSS);
  watch('src/css/**/*.css', minifyCSS);
  watch('src/js/**/*.js', minifyJS);
  //watch('C:\/xampp\/htdocs\/gulp\/wp-content\/uploads/*.{jpg,jpeg,png,svg,gif}', optimizeMediaLibrary);
  //watch('images/*.{jpg,jpeg,png,svg,gif}', optimizeStaticImages);
  watch("**/*.php", reload);
 }


export const watchMediaLibrary = () => {
  watch('C:\/xampp\/htdocs\/gulp\/wp-content\/uploads/*.{jpg,jpeg,png,svg,gif}', series(optimizeMediaLibrary, reload));
}


export const dev = series(clean, parallel(minifyCSS, minifyJS, optimizeMediaLibrary, optimizeStaticImages), serve, watchChanges);
//export const build = series(clean, minifySCSS, minifyCSS, optimizeMediaLibrary);
export const build = series(clean, parallel(minifyCSS, minifyJS, optimizeMediaLibrary, optimizeStaticImages), pot);
export default dev;
