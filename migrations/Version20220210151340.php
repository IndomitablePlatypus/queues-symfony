<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220210151340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "requirements" (id UUID NOT NULL, plan_id UUID NOT NULL, description TEXT NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, removed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX requirements_plan_id_idx ON "requirements" (plan_id)');
        $this->addSql('CREATE INDEX requirements_added_at_idx ON "requirements" (added_at)');
        $this->addSql('CREATE INDEX requirements_launched_at_idx ON "requirements" (removed_at)');
        $this->addSql('COMMENT ON COLUMN "requirements".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "requirements".plan_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "requirements" ADD CONSTRAINT fk_requirements_plans_plan_id FOREIGN KEY (plan_id) REFERENCES "plans" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "requirements"');
    }
}
