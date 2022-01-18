USE wpcr;

INSERT INTO `user` (`id`, `username`, `first_name`, `last_name`, `password`, `email`, `confirmation_token`, `confirmation_token_validity`, `last_login`, `locale`, `admin`, `locked`, `created_at`) VALUES
    (1, 'test', 'John', 'Doe', '$2y$12$QH3n2eXBzdLB1kqdiXGbrO9m23HlxlzDj0vgW7zRMvOatRR7jdS7u', 'test@test.test', NULL, NULL, NULL, 'en', 1, 0, '2022-01-18 00:55:39');
