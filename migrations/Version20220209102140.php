<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220209102140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "plans" (id UUID NOT NULL, workspace_id UUID NOT NULL, profile JSONB NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, launched_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, stopped_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, archived_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, expiration_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX plans_workspace_id_idx ON "plans" (workspace_id)');
        $this->addSql('CREATE INDEX plans_added_at_idx ON "plans" (added_at)');
        $this->addSql('CREATE INDEX plans_launched_at_idx ON "plans" (launched_at)');
        $this->addSql('CREATE INDEX plans_stopped_at_idx ON "plans" (stopped_at)');
        $this->addSql('CREATE INDEX plans_archived_at_idx ON "plans" (archived_at)');
        $this->addSql('CREATE INDEX plans_expiration_date_idx ON "plans" (expiration_date)');
        $this->addSql('COMMENT ON COLUMN "plans".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "plans".workspace_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "plans" ADD CONSTRAINT fk_plans_workspaces_workspace_id FOREIGN KEY (workspace_id) REFERENCES "workspaces" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "plans"');
    }
}
