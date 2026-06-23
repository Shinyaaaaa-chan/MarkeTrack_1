-- ========================================
-- Update customers table for MarkeTrack
-- Add profile_pic and gender columns
-- ========================================

ALTER TABLE customers
    ADD profile_pic VARCHAR(255) NOT NULL DEFAULT 'default.png' AFTER email,
    ADD gender ENUM('Male','Female','Other') NULL DEFAULT NULL AFTER profile_pic;
