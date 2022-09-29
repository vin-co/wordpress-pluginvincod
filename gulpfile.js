// Grab our gulp packages
var gulp = require('gulp'),
	gutil = require('gulp-util'),
	sass = require('gulp-sass')(require('node-sass')),
	cssnano = require('gulp-cssnano'),
	autoprefixer = require('gulp-autoprefixer'),
	sourcemaps = require('gulp-sourcemaps'),
	uglify = require('gulp-uglify'),
	plumber = require('gulp-plumber'),
	browserify = require('browserify'),
	source = require('vinyl-source-stream'),
	buffer = require('vinyl-buffer'),
	browserSync = require('browser-sync').create();

// ----- Variables -----

var assetsDir = 'assets';

var paths = {
	sassFiles: assetsDir + '/sass/**/*.scss',
	cssDir: assetsDir + '/css/',
	jsFiles: assetsDir + '/js/**/*.js',
	jsDir: assetsDir + '/js/',
	projectFiles: '**/*.{php,mo,po}',
	imagesFiles: assetsDir + '/img/**/*'
};

var sassConfig = {
	outputStyle: 'nested',
	precision: 10,
	includePaths: ['node_modules/']
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
			overrideBrowserslist: ['last 10 versions'],
			cascade: false
		}))
		.pipe(gulp.dest(paths.cssDir));
}

gulp.task('styles', function() {
	return compileSass()
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(paths.cssDir))
		.pipe(browserSync.stream({ match: "**/*.css" }));
});

gulp.task('styles-min', function() {
	return compileSass()
		.pipe(cssnano())
		.pipe(gulp.dest(paths.cssDir))
		.pipe(browserSync.stream({ match: "**/*.css" }));
});

// ----- Concatenate and minify Vendor JS -----

function compileVendorJs() {

	return browserify(paths.jsDir + 'imports.js')
		.bundle()
		.pipe(source('vendor.js'))
		.pipe(buffer())
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

// ----- Watch files for changes (without Browser-Sync) -----

gulp.task('watch', function() {
	// Watch .scss files
	gulp.watch(paths.sassFiles, gulp.series('styles-min'));
});

// ----- Build Tasks -----

gulp.task('build', gulp.series('styles', 'scripts'));
gulp.task('dist', gulp.series('styles-min', 'scripts-min'));

// ----- Browser-Sync watch files and inject changes -----

gulp.task('browsersync', gulp.series('build', gulp.parallel('watch', function() {
	// Watch files
	var files = [
		paths.jsFiles,
		paths.projectFiles,
		paths.imagesFiles,
	];

	browserSync.init(files, {
		proxy: 'https://plugin-vincod.test/',
		ghostMode: false
	});
})));

// ----- Default Task -----

gulp.task('default', gulp.series('dist'));
