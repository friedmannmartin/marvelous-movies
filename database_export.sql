-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost
-- Vytvořeno: Pát 22. kvě 2020, 19:55
-- Verze serveru: 10.3.22-MariaDB-log
-- Verze PHP: 7.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `frim00`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `forgotten_passwords`
--

CREATE TABLE `forgotten_passwords` (
  `forgotten_password_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `created` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `forgotten_passwords`
--

INSERT INTO `forgotten_passwords` (`forgotten_password_id`, `user_id`, `code`, `created`) VALUES
(7, 8, 'xx180785', '2020-05-20 22:47:03'),
(8, 8, 'xx919419', '2020-05-21 15:39:55');

-- --------------------------------------------------------

--
-- Struktura tabulky `movies`
--

CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `age_restriction` varchar(5) COLLATE utf8mb4_czech_ci DEFAULT 'G',
  `year` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_czech_ci NOT NULL,
  `length` int(11) NOT NULL,
  `trailer` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `poster` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `movies`
--

INSERT INTO `movies` (`movie_id`, `url`, `name`, `age_restriction`, `year`, `description`, `length`, `trailer`, `poster`) VALUES
(1, 'iron-man', 'Iron Man', 'PG-13', 2008, '2008\'s Iron Man tells the story of Tony Stark, a billionaire industrialist and genius inventor who is kidnapped and forced to build a devastating weapon. Instead, using his intelligence and ingenuity, Tony builds a high-tech suit of armor and escapes captivity. When he uncovers a nefarious plot with global implications, he dons his powerful armor and vows to protect the world as Iron Man.', 126, '8ugaeA-nMTc', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/ironman_lob_crd_01_4.jpg'),
(2, 'the-incredible-hulk', 'The Incredible Hulk', 'PG-13', 2008, 'In this new beginning, scientist Bruce Banner desperately hunts for a cure to the gamma radiation that poisoned his cells and unleashes the unbridled force of rage within him: The Hulk. Living in the shadows--cut off from a life he knew and the woman he loves, Betty Ross--Banner struggles to avoid the obsessive pursuit of his nemesis, General Thunderbolt Ross and the military machinery that seeks to capture him and brutally exploit his power. As all three grapple with the secrets that led to the Hulk\'s creation, they are confronted with a monstrous new adversary known as the Abomination, whose destructive strength exceeds even the Hulk\'s own. One scientist must make an agonizing final choice: accept a peaceful life as Bruce Banner or find heroism in the creature he holds inside--The Incredible Hulk.', 112, 'xbqNb2PFKKA', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/theincrediblehulk_lob_crd_01_3.jpg'),
(3, 'iron-man-2', 'Iron Man 2', 'PG-13', 2010, 'With the world now aware that he is Iron Man, billionaire inventor Tony Stark faces pressure from all sides to share his technology with the military. He is reluctant to divulge the secrets of his armored suit, fearing the information will fall into the wrong hands. With Pepper Potts and \"Rhodey\" Rhodes by his side, Tony must forge new alliances and confront a powerful new enemy.', 124, 'qsRZghNciIo', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/ironman2_lob_crd_01_4.jpg'),
(4, 'thor', 'Thor', 'PG-13', 2011, 'As the son of Odin, king of the Norse gods, Thor will soon inherit the throne of Asgard from his aging father. However, on the day that he is to be crowned, Thor reacts with brutality when the gods\' enemies, the Frost Giants, enter the palace in violation of their treaty. As punishment, Odin banishes Thor to Earth. While Loki, Thor\'s brother, plots mischief in Asgard, Thor, now stripped of his powers, faces his greatest threat.', 115, 'w1k59SJ_-Uo', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/thor_lob_crd_01_1.jpg'),
(5, 'captain-america:-the-first-avenger', 'Captain America: The First Avenger', 'PG-13', 2011, 'Marvel\'s \"Captain America: The First Avenger\" focuses on the early days of the Marvel Universe when Steve Rogers volunteers to participate in an experimental program that turns him into the Super Soldier known as Captain America.', 124, 'qi5UTD7kEr0', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/captainamericathefirstavenger_lob_crd_01_0.jpg'),
(6, 'the-avengers', 'The Avengers', 'PG-13', 2012, 'Marvel Studios presents in association with Paramount Pictures \"Marvel\'s The Avengers\"--the super hero team up of a lifetime, featuring iconic Marvel super heroes Iron Man, the Incredible Hulk, Thor, Captain America, Hawkeye and Black Widow. When an unexpected enemy emerges that threatens global safety and security, Nick Fury, Director of the international peacekeeping agency known as S.H.I.E.L.D., finds himself in need of a team to pull the world back from the brink of disaster. Spanning the globe, a daring recruitment effort begins.', 143, 'sXT4uBpGxNY', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/theavengers_lob_crd_03_0.jpg'),
(7, 'iron-man-3', 'Iron Man 3', 'PG-13', 2013, 'Marvel\'s \"Iron Man 3\" pits brash-but-brilliant industrialist Tony Stark/Iron Man against an enemy whose reach knows no bounds. When Stark finds his personal world destroyed at his enemy\'s hands, he embarks on a harrowing quest to find those responsible. This journey, at every turn, will test his mettle. With his back against the wall, Stark is left to survive by his own devices, relying on his ingenuity and instincts to protect those closest to him. As he fights his way back, Stark discovers the answer to the question that has secretly haunted him: does the man make the suit or does the suit make the man', 130, 'aV8H7kszXqo', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/ironman3_lob_crd_01_11.jpg'),
(8, 'thor:-the-darl-world', 'Thor: The Dark World', 'PG-13', 2013, 'In the aftermath of Marvel\'s \"Thor\" and \"Marvel\'s The Avengers,\" Thor fights to restore order across the cosmos...but an ancient race led by the vengeful Malekith returns to plunge the universe back into darkness. Faced with an enemy that even Odin and Asgard cannot withstand, Thor must embark on his most perilous and personal journey yet, one that will reunite him with Jane Foster and force him to sacrifice everything to save us all.', 112, 'vXxw3KkAJlI', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/thorthedarkworld_lob_crd_02.jpg'),
(9, 'captain-america:-the-winter-soldier', 'Captain America: The Winter Soldier', 'PG-13', 2014, 'After the cataclysmic events in New York with The Avengers, Marvel\'s \"Captain America: The Winter Soldier,\" finds Steve Rogers, aka Captain America, living quietly in Washington, D.C. and trying to adjust to the modern world. But when a S.H.I.E.L.D. colleague comes under attack, Steve becomes embroiled in a web of intrigue that threatens to put the world at risk. Joining forces with the Black Widow, Captain America struggles to expose the ever-widening conspiracy while fighting off professional assassins sent to silence him at every turn. When the full scope of the villainous plot is revealed, Captain America and the Black Widow enlist the help of a new ally, the Falcon. However, they soon find themselves up against an unexpected and formidable enemy--the Winter Soldier.', 136, 'Zmd6qLxMlQA', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/captainamericathewintersoldier_lob_crd_01_2.jpg'),
(10, 'guardians-of-the-galaxy', 'Guardians of the Galaxy', 'PG-13', 2014, 'An action-packed, epic space adventure, Marvel\'s \"Guardians of the Galaxy,\" expands the Marvel Cinematic Universe into the cosmos, where brash adventurer Peter Quill finds himself the object of an unrelenting bounty hunt after stealing a mysterious orb coveted by Ronan, a powerful villain with ambitions that threaten the entire universe. To evade the ever-persistent Ronan, Quill is forced into an uneasy truce with a quartet of disparate misfits--Rocket, a gun-toting raccoon; Groot, a tree-like humanoid; the deadly and enigmatic Gamora; and the revenge-driven Drax the Destroyer. But when Quill discovers the true power of the orb and the menace it poses to the cosmos, he must do his best to rally his ragtag rivals for a last, desperate stand--with the galaxy\'s fate in the balance.', 121, '2XltzyLcu0g', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/guardiansofthegalaxy_lob_crd_03_0.jpg'),
(11, 'avengers:-age-of-ultron', 'Avengers: Age of Ultron', 'PG-13', 2015, 'Marvel Studios presents “Avengers: Age of Ultron,” the epic follow-up to the biggest Super Hero movie of all time. When Tony Stark tries to jumpstart a dormant peacekeeping program, things go awry and Earth’s Mightiest Heroes, including Iron Man, Captain America, Thor, The Incredible Hulk, Black Widow and Hawkeye, are put to the ultimate test as the fate of the planet hangs in the balance. As the villainous Ultron emerges, it is up to the Avengers to stop him from enacting his terrible plans, and soon uneasy alliances and unexpected action pave the way for an epic and unique global adventure.', 141, 'u1OKBqHICMQ', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/avengersageofultron_lob_crd_03_0.jpg'),
(12, 'ant-man', 'Ant-Man', 'PG-13', 2015, 'The next evolution of the Marvel Cinematic Universe brings a founding member of The Avengers to the big screen for the first time with Marvel Studios\' \"Ant-Man.\" Armed with the astonishing ability to shrink in scale but increase in strength, master thief Scott Lang must embrace his inner-hero and help his mentor, Doctor Hank Pym, protect the secret behind his spectacular Ant-Man suit from a new generation of towering threats. Against seemingly insurmountable obstacles, Pym and Lang must plan and pull off a heist that will save the world.', 117, 'QfOZWGLT1JM', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/ant-man_lob_crd_01_9.jpg'),
(13, 'captain-america:-civil-war', 'Captain America: Civil War', 'PG-13', 2016, 'Marvel’s “Captain America: Civil War” finds Steve Rogers leading the newly formed team of Avengers in their continued efforts to safeguard humanity. But after another incident involving the Avengers results in collateral damage, political pressure mounts to install a system of accountability, headed by a governing body to oversee and direct the team. The new status quo fractures the Avengers, resulting in two camps—one led by Steve Rogers and his desire for the Avengers to remain free to defend humanity without government interference, and the other following Tony Stark’s surprising decision to support government oversight and accountability.', 147, 'FkTybqcX-Yo', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/captainamericacivilwar_lob_crd_01_10.jpg'),
(14, 'doctor-strange', 'Doctor Strange', 'PG-13', 2016, 'From Marvel Studios comes “Doctor Strange,” the story of world-famous neurosurgeon Dr. Stephen Strange whose life changes forever after a horrific car accident robs him of the use of his hands. When traditional medicine fails him, he is forced to look for healing, and hope, in an unlikely place—a mysterious enclave known as Kamar-Taj. Before long Strange—armed with newly acquired magical powers—is forced to choose whether to return to his life of fortune and status or leave it all behind to defend the world as the most powerful sorcerer in existence.', 115, 'h7gvFravm4A', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/doctorstrange_lob_crd_01_7.jpg'),
(15, 'guardians-of-the-galaxy:-vol.-2', 'Guardians of the Galaxy: Vol. 2', 'PG-13', 2017, 'Set to the backdrop of ‘Awesome Mixtape #2,’ Marvel’s Guardians of the Galaxy Vol. 2 continues the team’s adventures as they traverse the outer reaches of the cosmos. The Guardians must fight to keep their newfound family together as they unravel the mysteries of Peter Quill’s true parentage. Old foes become new allies and fan-favorite characters from the classic comics will come to our heroes’ aid as the Marvel cinematic universe continues to expand.', 136, 'pr7tDrwQ3t8', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/guardiansofthegalaxyvol.2_lob_crd_01_0.jpg'),
(16, 'spider-man:-momecoming', 'Spider-Man: Homecoming', 'PG-13', 2017, 'A young Peter Parker/Spider-Man (Tom Holland), who made his sensational debut in Captain America: Civil War, begins to navigate his newfound identity as the web-slinging super hero in Spider-Man: Homecoming. Thrilled by his experience with the Avengers, Peter returns home, where he lives with his Aunt May (Marisa Tomei), under the watchful eye of his new mentor Tony Stark (Robert Downey, Jr.). Peter tries to fall back into his normal daily routine – distracted by thoughts of proving himself to be more than just your friendly neighborhood Spider-Man – but when the Vulture (Michael Keaton) emerges as a new villain, everything that Peter holds most important will be threatened.', 133, 'n9DwoQ7HWvI', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/spider-manhomecoming_lob_crd_01_4.jpg'),
(17, 'thor:-ragnarok', 'Thor: Ragnarok', 'PG-13', 2017, 'Thor is imprisoned on the other side of the universe without his mighty hammer and finds himself in a race against time to get back to Asgard to stop Ragnarok—the destruction of his homeworld and the end of Asgardian civilization—at the hands of an all-powerful new threat, the ruthless Hela. But first he must survive a deadly gladiatorial contest that pits him against his former ally and fellow Avenger—the Incredible Hulk!', 130, 'e5HP-iUvlC4', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/thorragnarok_lob_crd_03_0.jpg'),
(18, 'black-panther', 'Black Panther', 'PG-13', 2018, 'Marvel Studios’ “Black Panther” follows T’Challa who, after the death of his father, the King of Wakanda, returns home to the isolated, technologically advanced African nation to succeed to the throne and take his rightful place as king. But when a powerful old enemy reappears, T’Challa’s mettle as king—and Black Panther—is tested when he is drawn into a formidable conflict that puts the fate of Wakanda and the entire world at risk. Faced with treachery and danger, the young king must rally his allies and release the full power of Black Panther to defeat his foes and secure the safety of his people and their way of life.', 134, 'oHLU3T-e2t4', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/blackpanther_lob_crd_01_5.jpg'),
(19, 'avengers:-infinity-war', 'Avengers: Infinity War', 'PG-13', 2018, 'An unprecedented cinematic journey ten years in the making and spanning the entire Marvel Cinematic Universe, Marvel Studios\' \"Avengers: Infinity War\" brings to the screen the ultimate, deadliest showdown of all time. As the Avengers and their allies have continued to protect the world from threats too large for any one hero to handle, a new danger has emerged from the cosmic shadows: Thanos. A despot of intergalactic infamy, his goal is to collect all six Infinity Stones, artifacts of unimaginable power, and use them to inflict his twisted will on all of reality. Everything the Avengers have fought for has led up to this moment - the fate of Earth and existence itself has never been more uncertain.\r\n', 149, 'B65hW9YYY5A', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/avengersinfinitywar_lob_crd_02.jpg'),
(20, 'ant-man-and-the-wasp', 'Ant-Man and the Wasp', 'PG-13', 2018, 'From the Marvel Cinematic Universe comes a new chapter featuring heroes with the astonishing ability to shrink: “Ant-Man and The Wasp.” In the aftermath of “Captain America: Civil War,” Scott Lang (Paul Rudd) grapples with the consequences of his choices as both a Super Hero and a father. As he struggles to rebalance his home life with his responsibilities as Ant-Man, he’s confronted by Hope van Dyne (Evangeline Lilly) and Dr. Hank Pym (Michael Douglas) with an urgent new mission. Scott must once again put on the suit and learn to fight alongside The Wasp as the team works together to uncover secrets from their past.', 118, 'fmrdsRdYZlg', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/ant-manthewasp_lob_crd_01_0.jpg'),
(21, 'captain-marvel', 'Captain Marvel', 'PG-13', 2019, 'Set in the 1990s, Marvel Studios\' \"Captain Marvel\" is an all-new adventure from a previously unseen period in the history of the Marvel Cinematic Universe that follows the journey of Carol Danvers as she becomes one of the universe\'s most powerful heroes. While a galactic war between two alien races reaches Earth, Danvers finds herself and a small cadre of allies at the center of the maelstrom.', 125, '0UUeH8DF8uA', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/captainmarvel_lob_crd_06.jpg'),
(22, 'avengers:-endgame', 'Avengers: Endgame', 'PG-13', 2019, 'The grave course of events set in motion by Thanos that wiped out half the universe and fractured the Avengers ranks compels the remaining Avengers to take one final stand in Marvel Studios\' grand conclusion to twenty-two films, \"Avengers: Endgame.\"', 182, 'ee1172yeqyE', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/avengersendgame_lob_crd_05.jpg'),
(23, 'spider-man:-far-from-home', 'Spider-Man: Far From Home', 'PG-13', 2019, 'Following the events of Avengers: Endgame, Spider-Man must step up to take on new threats in a world that has changed forever.', 129, 'Nt9L1jCKGnE', 'https://terrigen-cdn-dev.marvel.com/content/prod/1x/spider-manfarfromhome_lob_crd_04_0.jpg'),
(26, 'test', 'Test2', 'R', 1970, 'test test', 1, 'txIUXG29vzc', 'https://cdn4.buysellads.net/uu/1/65221/1589306848-Carbon_CTA-Copy-2_2x.jpg');

-- --------------------------------------------------------

--
-- Struktura tabulky `projections`
--

CREATE TABLE `projections` (
  `projection_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `language` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `subtittles` varchar(50) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `dimensions` char(2) COLLATE utf8mb4_czech_ci NOT NULL,
  `capacity` int(11) NOT NULL,
  `hall` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `projections`
--

INSERT INTO `projections` (`projection_id`, `movie_id`, `datetime`, `language`, `subtittles`, `dimensions`, `capacity`, `hall`) VALUES
(1, 1, '2020-05-21 18:00:00', 'EN', 'CS', '2D', 50, 'Main');

-- --------------------------------------------------------

--
-- Struktura tabulky `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `projection_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `facebook_id` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL DEFAULT '',
  `name` varchar(100) COLLATE utf8mb4_czech_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL DEFAULT '',
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`user_id`, `facebook_id`, `name`, `email`, `password`, `admin`) VALUES
(5, '3468338176527498', 'Martin Friedmann', 'friedmann.m2008@gmail.com', '', 0),
(7, '', 'Admin', 'admin@domena.cz', '$2y$10$kNNgGrJriu5GN/PA5gi0Men5Lexo4ku2Oti7JfYTgiaUkgUK7gm3O', 1),
(8, '', 'Email Test', 'frim00@vse.cz', '$2y$10$RLI.B0gcGredFDVZ8yx6fuNqaeH9u1qhsHcEfjkkPQIg1CJa7gn1.', 0),
(11, '', 'John Doe', 'john@doe.com', '$2y$10$eSFsnQk7WBTQUbvYrcw3xe8avSKFAGK4ARA.oSlBZyK023Oj.pxS.', 0);

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `forgotten_passwords`
--
ALTER TABLE `forgotten_passwords`
  ADD PRIMARY KEY (`forgotten_password_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Klíče pro tabulku `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`),
  ADD UNIQUE KEY `url_name` (`url`);

--
-- Klíče pro tabulku `projections`
--
ALTER TABLE `projections`
  ADD PRIMARY KEY (`projection_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Klíče pro tabulku `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `projection_id` (`projection_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Klíče pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `forgotten_passwords`
--
ALTER TABLE `forgotten_passwords`
  MODIFY `forgotten_password_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pro tabulku `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pro tabulku `projections`
--
ALTER TABLE `projections`
  MODIFY `projection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pro tabulku `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `forgotten_passwords`
--
ALTER TABLE `forgotten_passwords`
  ADD CONSTRAINT `forgotten_passwords_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `projections`
--
ALTER TABLE `projections`
  ADD CONSTRAINT `projections_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON UPDATE CASCADE;

--
-- Omezení pro tabulku `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`projection_id`) REFERENCES `projections` (`projection_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
