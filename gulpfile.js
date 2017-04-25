// Grab our gulp packages
var gulp  = require('gulp'),
	gutil = require('gulp-util'),
	sass = require('gulp-sass'),
	cssnano = require('gulp-cssnano'),
	autoprefixer = require('gulp-autoprefixer'),
	sourcemaps = require('gulp-sourcemaps'),
	uglify = require('gulp-uglify'),
	concat = require('gulp-concat'),
	rename = require('gulp-rename'),
	plumber = require('gulp-plumber'),
	wiredep = require('wiredep'),
	browserSync = require('browser-sync').create(),
	path = require('path');


// ----- Variables -----

var projectDir = path.resolve(__dirname);
var assetsDir = projectDir + '/assets';

var paths = {
	sassFiles:    assetsDir + '/sass/**/*.scss',
	cssDir:       assetsDir + '/css/',
	jsFiles:      assetsDir + '/js/**/*.js',
	jsDir:        assetsDir + '/js/',
	projectFiles: projectDir + '/**/*.{php,mo,po}',
	imagesFiles:  assetsDir + '/img/**/*'
};

var sassConfig = {
	outputStyle: 'nested',
	includePaths: [
    projectDir + '/node_modules/compass-mixins/lib',
    assetsDir + '/bower_components/'
	],
	precision: 10
};


// ----- Compile Sass, Autoprefix and minify -----

function compileSass() {
  return gulp.src(paths.sassFiles)
    .pipe(plumber(function(error) {
      gutil.log(gutil.colors.red(error.message));
      browserSync.notify(error.message, 10000);
      this.emit('end');
    }))
    .pipe(sourcemaps.init())
    .pipe(sass(sassConfig))
    .pipe(autoprefixer({
      browsers: ['last 10 versions'],
      cascade: false
    }))
    .pipe(gulp.dest(paths.cssDir));
}

gulp.task('styles', function() {
	return compileSass()
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(paths.cssDir))
		.pipe(browserSync.stream({match: "**/*.css"}));
});

gulp.task('styles-min', function() {
	return compileSass()
		.pipe(cssnano())
		.pipe(gulp.dest(paths.cssDir))
		.pipe(browserSync.stream({match: "**/*.css"}));
});

// ----- Concatenate and minify Vendor JS -----

function compileVendorJs() {
  var vendorFiles = wiredep().js;

  return gulp.src(vendorFiles)
    .pipe(plumber(function(error) {
      gutil.log(gutil.colors.red(error.message));
      browserSync.notify(error.message, 10000);
      this.emit('end');
    }))
    .pipe(concat('vendor.js'))
    .pipe(gulp.dest(paths.jsDir));
}

gulp.task('scripts', function() {
  return compileVendorJs();
});

gulp.task('scripts-min', function() {
	return compileVendorJs()
		.pipe(uglify())
		.pipe(gulp.dest(paths.jsDir));
});


// ----- Fonts -----

gulp.task('fonts', function() {
	var ionicons = assetsDir + '/bower_components/ionicons/fonts/ionicons.*';

	return gulp.src(ionicons)
		.pipe(gulp.dest(assetsDir + '/fonts/'))
});


// ----- Browser-Sync watch files and inject changes -----

gulp.task('browsersync', ['dist'], function() {
	// Watch files
	var files = [
		paths.jsFiles,
		paths.projectFiles,
		paths.imagesFiles,
		paths.videoFiles
	];

	browserSync.init(files, {
		proxy: 'http://wordpress-vincod.dev/',
    ghostMode: false
	});

	// Watch .scss files
	gulp.watch(paths.sassFiles, ['styles-min']);
});


// ----- Watch files for changes (without Browser-Sync) -----

gulp.task('watch', function() {
	// Watch .scss files
	gulp.watch(paths.sassFiles, ['styles-min']);

});


// ----- Build Tasks -----

gulp.task('build', ['styles', 'scripts', 'fonts']);
gulp.task('dist', ['styles-min', 'scripts-min', 'fonts']);


// ----- Default Task -----

gulp.task('default', function() {
	gulp.start('build');
});
