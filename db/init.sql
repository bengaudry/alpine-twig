START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `alpine-twig-blog`
--
CREATE DATABASE IF NOT EXISTS `alpine-twig-blog` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `alpine-twig-blog`;


-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `articles` (
    `id`         INT(11)       NOT NULL,
    `imageUrl`   VARCHAR(255),
    `titre`      VARCHAR(100)  NOT NULL,
    `date`       VARCHAR(10)   NOT NULL,
    `resume`     VARCHAR(100)  NOT NULL,
    `contenu`    VARCHAR(3000) NOT NULL
);

ALTER TABLE `articles`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `articles`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;
