<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220214160012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Relations';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE TABLE relations (
                id uuid NOT NULL, 
                collaborator_id uuid NOT NULL,
                workspace_id uuid NOT NULL,
                relation_type VARCHAR(255) NOT NULL,
                established_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql('CREATE INDEX relations_collaborator_id_idx ON "relations" (collaborator_id)');
        $this->addSql('CREATE INDEX relations_workspace_id_idx ON "relations" (workspace_id)');
        $this->addSql('CREATE INDEX relations_relation_type_idx ON "relations" (relation_type)');
        $this->addSql('CREATE INDEX relations_established_at_idx ON "relations" (established_at)');
        $this->addSql('COMMENT ON COLUMN "relations".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "relations".collaborator_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "relations".workspace_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "relations" ADD CONSTRAINT fk_relations_users_collaborator_id FOREIGN KEY (collaborator_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "relations" ADD CONSTRAINT fk_relations_workspaces_id FOREIGN KEY (workspace_id) REFERENCES "workspaces" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "relations"');
    }
}
