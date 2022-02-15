<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220215111111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Invites';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE TABLE invites (
                id uuid NOT NULL, 
                workspace_id uuid NOT NULL,
                proposed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql('CREATE INDEX invites_workspace_id_idx ON "invites" (workspace_id)');
        $this->addSql('CREATE INDEX invites_proposed_at_idx ON "invites" (proposed_at)');
        $this->addSql('COMMENT ON COLUMN "invites".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "invites".collaborator_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "invites".workspace_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "invites" ADD CONSTRAINT fk_invites_workspaces_collaborator_id FOREIGN KEY (workspace_id) REFERENCES "workspaces" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "invites"');
    }
}
