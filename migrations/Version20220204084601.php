<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220204084601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "workspaces" (id uuid NOT NULL, keeper_id uuid NOT NULL, profile jsonb NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX workspaces_keeper_id_idx ON "workspaces" (keeper_id)');
        $this->addSql('CREATE INDEX workspaces_added_at_idx ON "workspaces" (added_at)');
        $this->addSql('COMMENT ON COLUMN "workspaces".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "workspaces".keeper_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "workspaces".profile IS \'(DC2Type:array)\'');
        $this->addSql('CREATE INDEX tokens_user_id_idx ON tokens (user_id)');
        $this->addSql('CREATE INDEX tokens_name_idx ON tokens (name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "workspaces"');
        $this->addSql('DROP INDEX tokens_user_id_idx');
        $this->addSql('DROP INDEX tokens_name_idx');
    }
}
