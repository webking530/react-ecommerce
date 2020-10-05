pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        sh 'rsync -av --no-p --progress * /var/www/html/product/spiffy/ --exclude "config/database.php" --exclude "image/*"'
      }
    }
  }
}