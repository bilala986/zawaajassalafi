CREATE TABLE user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    
    -- Profile Setup
    gender ENUM('Male','Female') NOT NULL,
    age INT NOT NULL,
    country_of_residence VARCHAR(100) NOT NULL,
    ethnicity VARCHAR(100) NOT NULL,
    nationality VARCHAR(100) NOT NULL,
    marital_status ENUM('Never Married','Divorced','Widowed') NOT NULL,
    education_level VARCHAR(255) NOT NULL,
    occupation VARCHAR(255) NOT NULL,
    
    -- Preferences
    preferred_country VARCHAR(100),
    preferred_ethnicity VARCHAR(100),
    preferred_min_age INT,
    preferred_max_age INT,
    preferred_marital_status VARCHAR(50),
    relocation_preference ENUM('Yes','No','Maybe'),
    
    -- Additional Information
    islam_relation TEXT NOT NULL,
    marriage_vision TEXT NOT NULL,
    hobbies TEXT NOT NULL,
    spouse_role TEXT NOT NULL,
    spouse_qualities TEXT NOT NULL,
    religiosity_preference TEXT NOT NULL,
    scholars TEXT,
    texts_studied TEXT,
    additional_notes TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign Key
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users_auth(id) ON DELETE CASCADE
);
